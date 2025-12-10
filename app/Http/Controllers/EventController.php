<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Program;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
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
            $user = Auth::user();
            
            $events = Event::where('barangay_id', $user->barangay_id)
                ->orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            Log::info('Total events found for barangay ' . $user->barangay_id . ': ' . $events->count());

            $groupedEvents = $events->groupBy(function($event) {
                return Carbon::parse($event->event_date)->format('F Y');
            });

            $today = Carbon::today();
            $todayEvents = $events->filter(function($event) use ($today) {
                return Carbon::parse($event->event_date)->isSameDay($today);
            });

            Log::info('Today events count: ' . $todayEvents->count());

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

        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
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
            $eventId = (int)$id;
            Log::info("Fetching event with ID: {$eventId} for barangay: {$user->barangay_id}");

            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                Log::warning("Event not found with ID: {$eventId} for barangay: {$user->barangay_id}");
                return response()->json(['error' => 'Event not found'], 404);
            }

            Log::info("Event found: {$event->title}");

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
                'postponed' => (bool)$event->postponed,
                'postponed_to' => $event->postponed_to ? Carbon::parse($event->postponed_to)->format('Y-m-d H:i:s') : null,
                'postponed_reason' => $event->postponed_reason,
                'passcode' => $event->passcode,
                'barangay_id' => $event->barangay_id,
            ];

            if ($event->image) {
                try {
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

            try {
                if ($event->event_date && $event->event_time) {
                    $formattedDate = Carbon::parse($event->event_date)->format('F j, Y');
                    $formattedTime = $event->event_time;
                    
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
            'postponed' => false,
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
            $eventId = (int)$id;
            
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }
            
            $event->update([
                'is_launched' => true,
                'status' => 'ongoing',
                'postponed' => false, // Reset postponed when launching
                'postponed_to' => null,
                'postponed_reason' => null,
            ]);

            Log::info('Event launched: ' . $eventId . ' for barangay: ' . $user->barangay_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error launching event: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to launch event'], 500);
        }
    }

    /**
     * Postpone the specified event.
     */
    public function postponeEvent($id, Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $eventId = (int)$id;
            
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }

            $validated = $request->validate([
                'postponed_to' => 'required|date|after:today',
                'postponed_reason' => 'nullable|string|max:1000',
            ]);

            $postponedTo = Carbon::parse($validated['postponed_to']);
            
            $event->update([
                'postponed' => true,
                'postponed_to' => $postponedTo,
                'postponed_reason' => $validated['postponed_reason'] ?? null,
                'status' => 'postponed',
            ]);

            Log::info('Event postponed: ' . $eventId . ' to ' . $postponedTo . ' for barangay: ' . $user->barangay_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error postponing event: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to postpone event: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Unpostpone the specified event.
     */
    public function unpostponeEvent($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $eventId = (int)$id;
            
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }
            
            $event->update([
                'postponed' => false,
                'postponed_to' => null,
                'postponed_reason' => null,
                'status' => 'upcoming',
            ]);

            Log::info('Event unpostponed: ' . $eventId . ' for barangay: ' . $user->barangay_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error unpostponing event: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to unpostpone event'], 500);
        }
    }

    /**
     * Generate passcode for the specified event.
     */
    public function generatePasscode($id, Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $eventId = (int)$id;
            Log::info("Generating passcode for event ID: {$eventId} for barangay: {$user->barangay_id}");

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

            $event->passcode = $passcode;
            $event->save();

            Log::info("Passcode saved successfully for event: {$eventId}");

            return response()->json([
                'success' => true, 
                'passcode' => $passcode
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating passcode: ' . $e->getMessage());
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
            $eventId = (int)$id;
            
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
    public function userEvents(): View|RedirectResponse
    {
        try {
            $user = Auth::user();
            
            // Check if user is authenticated
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to view events.');
            }

            $today = Carbon::today();
            $currentDateTime = Carbon::now();

            Log::info("Loading events for user ID: {$user->id}, Barangay ID: {$user->barangay_id}");

            // === GET GENERAL NOTIFICATIONS (FIXED) ===
            $generalNotifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

            // Get LAUNCHED events from the SAME BARANGAY as the user
            $events = Event::where('is_launched', true)
                ->where('barangay_id', $user->barangay_id)
                ->where('postponed', false) // Don't show postponed events
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
            ->where('postponed', false)
            ->where('barangay_id', $user->barangay_id)
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

            // Calculate total notification count (general + unevaluated events)
            $notificationCount = $unreadNotificationCount + $unevaluatedEvents->count();

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

            // Compute role badge & age - with null checks
            $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
            $age = $user && $user->date_of_birth 
                ? Carbon::parse($user->date_of_birth)->age 
                : 'N/A';

            // Debug logging
            Log::info("=== EVENT DEBUG INFO ===");
            Log::info("User ID: " . $user->id);
            Log::info("User barangay_id: " . $user->barangay_id);
            Log::info("Total events: " . $events->count());
            Log::info("Total programs: " . $programs->count());
            Log::info("Today's events: " . $todayEvents->count());
            Log::info("Today's programs: " . $todayPrograms->count());
            Log::info("Notification count: " . $notificationCount);

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
                'generalNotifications',
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
            $user = Auth::user();

            // If user is not authenticated, redirect to login
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to view events.');
            }

            return view('eventpage', [
                'events' => collect(),
                'programs' => collect(),
                'todayEvents' => collect(),
                'todayPrograms' => collect(),
                'upcomingEvents' => collect(),
                'upcomingPrograms' => collect(),
                'user' => $user,
                'roleBadge' => $user ? (strtoupper($user->role) . '-Member') : 'GUEST',
                'age' => $user && $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A',
                'unevaluatedEvents' => collect(),
                'generalNotifications' => collect(),
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
                ->where('postponed', false)
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
            
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }

            if (!$event->passcode) {
                $event->passcode = $this->generateRandomPasscode();
                $event->save();
            }

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

            $attendedEvents = Event::whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('attended_at');
            })
            ->where('barangay_id', $user->barangay_id)
            ->where('postponed', false)
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->with(['evaluations' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->where('is_launched', true)
            ->orderBy('event_date', 'desc')
            ->get();

            Log::info("Found {$attendedEvents->count()} attended events for user {$user->id}");

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

            $program = Program::where('id', $programId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$program) {
                Log::warning("Program not found with ID: {$programId} for barangay: {$user->barangay_id}");
                return response()->json(['error' => 'Program not found'], 404);
            }

            Log::info("Program found: {$program->title}");

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

            if ($program->display_image) {
                try {
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

            $eventId = (int)$id;
            
            $event = Event::where('id', $eventId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$event) {
                Log::warning("Event not found for editing with ID: {$eventId} for barangay: {$user->barangay_id}");
                return redirect()->route('sk-eventpage')->with('error', 'Event not found.');
            }

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

            $eventId = (int)$id;
            
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

            if ($request->hasFile('image')) {
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                $validated['image'] = $request->file('image')->store('events', 'public');
            }

            $event->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'event_date' => $validated['event_date'],
                'event_time' => $validated['event_time'],
                'location' => $validated['location'],
                'category' => $validated['category'],
                'published_by' => $validated['published_by'],
                'image' => $validated['image'] ?? $event->image,
            ]);

            Log::info('Event updated successfully with ID: ' . $event->id . ' for barangay: ' . $user->barangay_id);

            return redirect()->route('sk-eventpage')->with('success', 'Event updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error updating event: ' . $e->getMessage());
        }
    }
}