<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Program;
use App\Models\Attendance;
use App\Models\Evaluation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(): View
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            
            // Get events from the SAME BARANGAY as the SK user
            $events = Event::where('barangay_id', $user->barangay_id)
                ->orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            Log::info('Total events found for barangay ' . $user->barangay_id . ': ' . $events->count());

            foreach ($events as $event) {
                Log::info("Event: {$event->id} - {$event->title} - {$event->event_date} - {$event->event_time} - Status: {$event->status} - Barangay: {$event->barangay_id}");
            }

            $groupedEvents = $events->groupBy(function($event) {
                return Carbon::parse($event->event_date)->format('F Y');
            });

            $today = Carbon::today(); // Keep as Carbon object
            $todayEvents = $events->filter(function($event) use ($today) {
                return Carbon::parse($event->event_date)->isSameDay($today);
            });

            Log::info('Today events count: ' . $todayEvents->count());
            Log::info('Grouped events months: ' . implode(', ', $groupedEvents->keys()->toArray()));

            // Calculate role badge and age for the user
            $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
            $age = $user && $user->date_of_birth 
                ? Carbon::parse($user->date_of_birth)->age 
                : 'N/A';

            return view('sk-eventpage', compact(
                'groupedEvents', 
                'todayEvents', 
                'events', 
                'user', 
                'roleBadge', 
                'age'
            ));
        } catch (\Exception $e) {
            Log::error('Error in events index: ' . $e->getMessage());
            
            $user = Auth::user();
            $roleBadge = 'GUEST';
            $age = 'N/A';
            
            return view('sk-eventpage', [
                'groupedEvents' => collect(),
                'todayEvents' => collect(),
                'events' => collect(),
                'user' => $user,
                'roleBadge' => $roleBadge,
                'age' => $age,
            ]);
        }
    }

    /**
     * Show the form for creating a new event.
     */
     public function create(): View|RedirectResponse
{
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    // Calculate age from date_of_birth
    $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

    // Determine role badge based on actual enum values
    $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

    return view('create-event', compact('user', 'age', 'roleBadge'));
}

    /**
     * Display the specified event.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = Auth::user();
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            Log::info("Fetching event with ID: {$eventId} for barangay: {$user->barangay_id}");

            // Only show events from the same barangay
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                Log::warning("Event not found with ID: {$eventId} for barangay: {$user->barangay_id}");
                return response()->json(['error' => 'Event not found'], 404);
            }

            Log::info("Event found: {$event->title}");

            // Build response data safely
            $responseData = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date ? Carbon::parse($event->event_date)->format('Y-m-d') : null,
                'event_time' => $event->event_time,
                'location' => $event->location,
                'category' => $event->category,
                'published_by' => $event->published_by,
                'status' => $event->status,
                'is_launched' => (bool)$event->is_launched,
                'passcode' => $event->passcode,
                'barangay_id' => $event->barangay_id,
            ];

            // Safely handle image URL
            if ($event->image) {
                try {
                    // Check if file exists in storage
                    if (Storage::disk('public')->exists($event->image)) {
                        $responseData['image'] = asset('storage/' . $event->image);
                    } else {
                        $responseData['image'] = null;
                        Log::warning("Image file not found: " . $event->image);
                    }
                    Log::info("Image URL: " . $responseData['image']);
                } catch (\Exception $e) {
                    Log::error("Error generating image URL: " . $e->getMessage());
                    $responseData['image'] = null;
                }
            } else {
                $responseData['image'] = null;
            }

            // Safely handle event_date_time
            try {
                if ($event->event_date && $event->event_time) {
                    $formattedDate = Carbon::parse($event->event_date)->format('F j, Y');
                    $formattedTime = $event->event_time;
                    
                    // Convert time to 12-hour format if needed
                    if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $event->event_time)) {
                        $formattedTime = Carbon::createFromFormat('H:i:s', $event->event_time)->format('h:i A');
                    } elseif (preg_match('/^\d{2}:\d{2}$/', $event->event_time)) {
                        $formattedTime = Carbon::createFromFormat('H:i', $event->event_time)->format('h:i A');
                    }
                    
                    $responseData['event_date_time'] = $formattedDate . ' | ' . $formattedTime;
                } else {
                    $responseData['event_date_time'] = 'Date not available';
                }
            } catch (\Exception $e) {
                Log::error("Error formatting event_date_time: " . $e->getMessage());
                $responseData['event_date_time'] = 'Date format error';
            }

            Log::info("Successfully prepared event data for ID: {$eventId}");
            
            return response()->json($responseData);
            
        } catch (\Exception $e) {
            Log::error('Error fetching event: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required|string',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_by' => 'required|string|max:255',
        ]);

        Log::info('Creating event with data:', $validated);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $eventData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'location' => $validated['location'],
            'category' => $validated['category'],
            'published_by' => $validated['published_by'],
            'status' => 'upcoming',
            'is_launched' => false,
            'user_id' => Auth::id(), 
            'barangay_id' => Auth::user()->barangay_id, 
        ];

        if (isset($validated['image'])) {
            $eventData['image'] = $validated['image'];
        }

        $event = Event::create($eventData);

        Log::info('Event created successfully with ID: ' . $event->id . ' for barangay: ' . Auth::user()->barangay_id);

        return redirect()->route('sk-eventpage')->with('success', 'Event created successfully!');
    }

    /**
     * Launch the specified event.
     */
    public function launchEvent($id): JsonResponse
    {
        try {
            $user = Auth::user();
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            
            // Only allow launching events from the same barangay
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }
            
            $event->update([
                'is_launched' => true,
                'status' => 'ongoing',
            ]);

            Log::info('Event launched: ' . $eventId . ' for barangay: ' . $user->barangay_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error launching event: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to launch event'], 500);
        }
    }

    /**
     * Generate passcode for the specified event.
     */
    public function generatePasscode($id, Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            Log::info("Generating passcode for event ID: {$eventId} for barangay: {$user->barangay_id}");
            Log::info("Request data: ", $request->all());

            // Only allow generating passcode for events from the same barangay
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                Log::error("Event not found with ID: {$eventId} for barangay: {$user->barangay_id}");
                return response()->json([
                    'success' => false, 
                    'error' => 'Event not found'
                ], 404);
            }

            $passcode = $request->passcode ?? $this->generateRandomPasscode();
            Log::info("Generated passcode: {$passcode}");

            // Update the event with passcode
            $event->passcode = $passcode;
            $event->save();

            Log::info("Passcode saved successfully for event: {$eventId}");

            return response()->json([
                'success' => true, 
                'passcode' => $passcode
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating passcode: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false, 
                'error' => 'Failed to generate passcode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::user();
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            
            // Only allow deleting events from the same barangay
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }

            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $event->delete();

            Log::info('Event deleted: ' . $eventId . ' from barangay: ' . $user->barangay_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to delete event'], 500);
        }
    }

    /**
     * Display events page for regular users
     */
    public function userEvents(): View
    {
        try {
            $user = Auth::user();
            $today = Carbon::today();
            $currentDateTime = Carbon::now();

            Log::info("Loading events for user ID: {$user->id}, Barangay ID: {$user->barangay_id}");

            // Get LAUNCHED events from the SAME BARANGAY as the user
            $events = Event::where('is_launched', true)
                ->where('barangay_id', $user->barangay_id)
                ->orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            // Get PROGRAMS from the SAME BARANGAY as the user
            $programs = Program::where('barangay_id', $user->barangay_id)
                ->orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            Log::info("Total launched events found for barangay {$user->barangay_id}: " . $events->count());
            Log::info("Total programs found for barangay {$user->barangay_id}: " . $programs->count());

            // Filter today's events - show ALL launched events happening today
            $todayEvents = $events->filter(function($event) use ($today) {
                return Carbon::parse($event->event_date)->isSameDay($today);
            });

            // Get today's programs
            $todayPrograms = $programs->filter(function($program) use ($today) {
                return Carbon::parse($program->event_date)->isSameDay($today);
            });

            // Get unevaluated events for notifications (same barangay)
            $unevaluatedEvents = Event::whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('is_launched', true)
            ->where('barangay_id', $user->barangay_id)
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

            $notificationCount = $unevaluatedEvents->count();

            // Filter upcoming events (considering date AND time) - only future launched events
            $upcomingEvents = $events->filter(function($event) use ($currentDateTime) {
                $eventDate = Carbon::parse($event->event_date);
                
                // Create full datetime object for the event
                if ($event->event_time) {
                    // Parse the time and combine with event date
                    $eventTime = Carbon::parse($event->event_time);
                    $eventDateTime = Carbon::create(
                        $eventDate->year,
                        $eventDate->month,
                        $eventDate->day,
                        $eventTime->hour,
                        $eventTime->minute,
                        $eventTime->second
                    );
                } else {
                    // If no time specified, use end of day
                    $eventDateTime = $eventDate->endOfDay();
                }
                
                // Only show events that haven't happened yet
                return $eventDateTime->gt($currentDateTime);
            });

            // Filter upcoming programs (considering date AND time) - only future programs
            $upcomingPrograms = $programs->filter(function($program) use ($currentDateTime) {
                $programDate = Carbon::parse($program->event_date);
                
                // Create full datetime object for the program
                if ($program->event_time) {
                    // Parse the time and combine with program date
                    $programTime = Carbon::parse($program->event_time);
                    $programDateTime = Carbon::create(
                        $programDate->year,
                        $programDate->month,
                        $programDate->day,
                        $programTime->hour,
                        $programTime->minute,
                        $programTime->second
                    );
                } else {
                    // If no time specified, use end of day
                    $programDateTime = $programDate->endOfDay();
                }
                
                // Only show programs that haven't happened yet
                return $programDateTime->gt($currentDateTime);
            });

            // Compute role badge & age
            $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
            $age = $user && $user->date_of_birth 
                ? Carbon::parse($user->date_of_birth)->age 
                : 'N/A';

            // Debug logging - VERY DETAILED
            Log::info("=== EVENT DEBUG INFO ===");
            Log::info("User barangay_id: " . $user->barangay_id);
            Log::info("Today's date: " . $today->format('Y-m-d'));
            Log::info("Current datetime: " . $currentDateTime->format('Y-m-d H:i:s'));
            Log::info("Total launched events found: " . $events->count());
            Log::info("Total programs found: " . $programs->count());
            Log::info("Today's events count: " . $todayEvents->count());
            Log::info("Today's programs count: " . $todayPrograms->count());
            Log::info("Upcoming events count: " . $upcomingEvents->count());
            Log::info("Upcoming programs count: " . $upcomingPrograms->count());
            Log::info("Unevaluated events count: " . $unevaluatedEvents->count());

            // Log ALL events for debugging
            foreach ($events as $event) {
                $eventDate = Carbon::parse($event->event_date);
                $isToday = $eventDate->isSameDay($today) ? 'YES' : 'NO';
                $isLaunched = $event->is_launched ? 'YES' : 'NO';
                
                Log::info("Event: {$event->id} - '{$event->title}' - Date: {$eventDate->format('Y-m-d')} - Today: {$isToday} - Launched: {$isLaunched} - Barangay: {$event->barangay_id}");
            }

            // Log ALL programs for debugging
            foreach ($programs as $program) {
                $programDate = Carbon::parse($program->event_date);
                $isToday = $programDate->isSameDay($today) ? 'YES' : 'NO';
                
                Log::info("Program: {$program->id} - '{$program->title}' - Date: {$programDate->format('Y-m-d')} - Today: {$isToday} - Barangay: {$program->barangay_id}");
            }

            // Log today's events specifically
            foreach ($todayEvents as $event) {
                $eventDate = Carbon::parse($event->event_date);
                Log::info("TODAY'S EVENT: {$event->id} - '{$event->title}' - Date: {$eventDate->format('Y-m-d')} - Time: {$event->event_time}");
            }

            // Log today's programs specifically
            foreach ($todayPrograms as $program) {
                $programDate = Carbon::parse($program->event_date);
                Log::info("TODAY'S PROGRAM: {$program->id} - '{$program->title}' - Date: {$programDate->format('Y-m-d')} - Time: {$program->event_time}");
            }

            return view('eventpage', compact(
                'events', 
                'programs',
                'todayEvents', 
                'todayPrograms',
                'upcomingEvents', 
                'upcomingPrograms',
                'user', 
                'roleBadge', 
                'age',
                'unevaluatedEvents',
                'notificationCount',
                'today',
                'currentDateTime'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading user events: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Provide default values for the missing variables
            $today = Carbon::today();
            $currentDateTime = Carbon::now();

            return view('eventpage', [
                'events' => collect(),
                'programs' => collect(),
                'todayEvents' => collect(),
                'todayPrograms' => collect(),
                'upcomingEvents' => collect(),
                'upcomingPrograms' => collect(),
                'user' => Auth::user(),
                'roleBadge' => 'GUEST',
                'age' => 'N/A',
                'unevaluatedEvents' => collect(),
                'notificationCount' => 0,
                'today' => $today,
                'currentDateTime' => $currentDateTime
            ]);
        }
    }

    /**
     * Display events for public viewing (launched events only)
     */
    public function publicIndex(): View
    {
        try {
            $today = Carbon::today();
            
            $events = Event::where('is_launched', true)
                ->where('event_date', '>=', $today)
                ->orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            return view('eventpage', compact('events'));
        } catch (\Exception $e) {
            Log::error('Error in events public index: ' . $e->getMessage());
            return view('eventpage', ['events' => collect()]);
        }
    }

    /**
     * Generate a random passcode.
     */
    private function generateRandomPasscode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Generate QR Code for event
     */
    public function generateQRCode($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $eventId = (int)$id;
            
            // Only allow QR generation for events from the same barangay
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }

            // Generate passcode if not exists
            if (!$event->passcode) {
                $event->passcode = $this->generateRandomPasscode();
                $event->save();
            }

            // QR code data - this will be scanned and sent to attendance endpoint
            $qrData = json_encode([
                'event_id' => $event->id,
                'passcode' => $event->passcode,
                'type' => 'attendance'
            ]);

            return response()->json([
                'success' => true,
                'qr_data' => $qrData,
                'passcode' => $event->passcode
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to generate QR code'], 500);
        }
    }

    /**
     * Get events attended by user for evaluation
     */
    public function getAttendedEvents(): View|RedirectResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }

            Log::info("Getting attended events for user: {$user->id}");

            // Get events that user has attended but not yet evaluated (same barangay)
            $attendedEvents = Event::whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('attended_at');
            })
            ->where('barangay_id', $user->barangay_id)
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->with(['evaluations' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->where('is_launched', true)
            ->orderBy('event_date', 'desc')
            ->get();

            Log::info("Found {$attendedEvents->count()} attended events for user {$user->id} in barangay {$user->barangay_id}");

            // Debug: Log each attended event
            foreach ($attendedEvents as $event) {
                Log::info("Attended event: {$event->id} - {$event->title} - {$event->event_date} - Barangay: {$event->barangay_id}");
            }

            // Compute role badge & age
            $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
            $age = $user && $user->date_of_birth 
                ? Carbon::parse($user->date_of_birth)->age 
                : 'N/A';

            return view('evaluationpage', compact(
                'attendedEvents',
                'user', 
                'roleBadge', 
                'age'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading attended events: ' . $e->getMessage());

            return view('evaluationpage', [
                'attendedEvents' => collect(), 
                'user' => Auth::user(),
                'roleBadge' => 'GUEST',
                'age' => 'N/A',
            ]);
        }
    }

    public function showQr($id)
    {
        $event = Event::findOrFail($id);

        // Get event title and passcode - FIXED: use 'title' instead of 'event_title'
        $title = $event->title ?? 'Untitled Event';
        $passcode = $event->passcode ?? 'N/A';

        return view('qr', [
            'title' => $title,
            'passcode' => $passcode,
            'event' => $event,
        ]);
    }

    /**
     * Display program details for modal
     */
    public function showProgram($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $programId = (int)$id;
            Log::info("Fetching program with ID: {$programId} for barangay: {$user->barangay_id}");

            // Only show programs from the same barangay
            $program = Program::where('id', $programId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$program) {
                Log::warning("Program not found with ID: {$programId} for barangay: {$user->barangay_id}");
                return response()->json(['error' => 'Program not found'], 404);
            }

            Log::info("Program found: {$program->title}");

            // Build response data safely
            $responseData = [
                'id' => $program->id,
                'title' => $program->title,
                'description' => $program->description,
                'event_date' => $program->event_date ? Carbon::parse($program->event_date)->format('Y-m-d') : null,
                'event_time' => $program->event_time,
                'location' => $program->location,
                'category' => $program->category,
                'published_by' => $program->published_by,
                'registration_type' => $program->registration_type,
                'link_source' => $program->link_source,
                'registration_description' => $program->registration_description,
                'barangay_id' => $program->barangay_id,
            ];

            // Safely handle display_image URL
            if ($program->display_image) {
                try {
                    // Check if file exists in storage
                    if (Storage::disk('public')->exists($program->display_image)) {
                        $responseData['display_image'] = asset('storage/' . $program->display_image);
                    } else {
                        $responseData['display_image'] = null;
                        Log::warning("Display image file not found: " . $program->display_image);
                    }
                    Log::info("Display image URL: " . $responseData['display_image']);
                } catch (\Exception $e) {
                    Log::error("Error generating display image URL: " . $e->getMessage());
                    $responseData['display_image'] = null;
                }
            } else {
                $responseData['display_image'] = null;
            }

            Log::info("Successfully prepared program data for ID: {$programId}");
            
            return response()->json($responseData);
            
        } catch (\Exception $e) {
            Log::error('Error fetching program: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
 * Show the form for editing the specified event.
 */
public function edit($id): View|RedirectResponse
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Convert to integer to ensure type consistency
        $eventId = (int)$id;
        
        // Only allow editing events from the same barangay
        $event = Event::where('id', $eventId)
                    ->where('barangay_id', $user->barangay_id)
                    ->first();
        
        if (!$event) {
            Log::warning("Event not found for editing with ID: {$eventId} for barangay: {$user->barangay_id}");
            return redirect()->route('sk-eventpage')->with('error', 'Event not found.');
        }

        // Calculate role badge and age for the user
        $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user && $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('edit-event', compact('event', 'user', 'roleBadge', 'age'));

    } catch (\Exception $e) {
        Log::error('Error loading edit event form: ' . $e->getMessage());
        return redirect()->route('sk-eventpage')->with('error', 'Error loading edit form.');
    }
}

/**
 * Update the specified event in storage.
 */
public function update(Request $request, $id): RedirectResponse
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Convert to integer to ensure type consistency
        $eventId = (int)$id;
        
        // Only allow updating events from the same barangay
        $event = Event::where('id', $eventId)
                    ->where('barangay_id', $user->barangay_id)
                    ->first();
        
        if (!$event) {
            Log::warning("Event not found for updating with ID: {$eventId} for barangay: {$user->barangay_id}");
            return redirect()->route('sk-eventpage')->with('error', 'Event not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required|string',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_by' => 'required|string|max:255',
        ]);

        Log::info('Updating event with data:', $validated);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        // Update event data
        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'location' => $validated['location'],
            'category' => $validated['category'],
            'published_by' => $validated['published_by'],
            'image' => $validated['image'] ?? $event->image, // Keep existing image if not updated
        ]);

        Log::info('Event updated successfully with ID: ' . $event->id . ' for barangay: ' . $user->barangay_id);

        return redirect()->route('sk-eventpage')->with('success', 'Event updated successfully!');

    } catch (\Exception $e) {
        Log::error('Error updating event: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Error updating event: ' . $e->getMessage());
    }
}
}
