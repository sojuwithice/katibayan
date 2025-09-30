<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(): View
    {
        try {
            $events = Event::orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            Log::info('Total events found: ' . $events->count());

            foreach ($events as $event) {
                Log::info("Event: {$event->id} - {$event->title} - {$event->event_date} - {$event->event_time} - Status: {$event->status}");
            }

            $groupedEvents = $events->groupBy(fn($event) => $event->event_date->format('F Y'));

            $today = now()->format('Y-m-d');
            $todayEvents = $events->filter(fn($event) => $event->event_date->format('Y-m-d') === $today);

            Log::info('Today events count: ' . $todayEvents->count());
            Log::info('Grouped events months: ' . implode(', ', $groupedEvents->keys()->toArray()));

            return view('sk-eventpage', compact('groupedEvents', 'todayEvents', 'events'));
        } catch (\Exception $e) {
            Log::error('Error in events index: ' . $e->getMessage());
            return view('sk-eventpage', [
                'groupedEvents' => collect(),
                'todayEvents' => collect(),
                'events' => collect(),
            ]);
        }
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        return view('create-event');
    }

    /**
     * Display the specified event.
     */
    public function show($id): JsonResponse
    {
        try {
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            Log::info("Fetching event with ID: {$eventId}");

            $event = Event::find($eventId);
            
            if (!$event) {
                Log::warning("Event not found with ID: {$eventId}");
                return response()->json(['error' => 'Event not found'], 404);
            }

            Log::info("Event found: {$event->title}");

            // Build response data safely
            $responseData = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date ? $event->event_date->format('Y-m-d') : null,
                'event_time' => $event->event_time,
                'location' => $event->location,
                'category' => $event->category,
                'published_by' => $event->published_by,
                'status' => $event->status,
                'is_launched' => (bool)$event->is_launched,
                'passcode' => $event->passcode,
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
                    $formattedDate = $event->event_date->format('F j, Y');
                    $formattedTime = $event->event_time;
                    
                    // Convert time to 12-hour format if needed
                    if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $event->event_time)) {
                        $formattedTime = \Carbon\Carbon::createFromFormat('H:i:s', $event->event_time)->format('h:i A');
                    } elseif (preg_match('/^\d{2}:\d{2}$/', $event->event_time)) {
                        $formattedTime = \Carbon\Carbon::createFromFormat('H:i', $event->event_time)->format('h:i A');
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
        ];

        if (isset($validated['image'])) {
            $eventData['image'] = $validated['image'];
        }

        $event = Event::create($eventData);

        Log::info('Event created successfully with ID: ' . $event->id);

        return redirect()->route('sk-eventpage')->with('success', 'Event created successfully!');
    }

    /**
     * Launch the specified event.
     */
    public function launchEvent($id): JsonResponse
    {
        try {
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            $event = Event::find($eventId);
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }
            
            $event->update([
                'is_launched' => true,
                'status' => 'ongoing',
            ]);

            Log::info('Event launched: ' . $eventId);
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
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            Log::info("Generating passcode for event ID: {$eventId}");
            Log::info("Request data: ", $request->all());

            $event = Event::find($eventId);
            
            if (!$event) {
                Log::error("Event not found with ID: {$eventId}");
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
            // Convert to integer to ensure type consistency
            $eventId = (int)$id;
            $event = Event::find($eventId);
            
            if (!$event) {
                return response()->json(['success' => false, 'error' => 'Event not found'], 404);
            }

            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $event->delete();

            Log::info('Event deleted: ' . $eventId);
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
            // Get today's date
            $today = now()->format('Y-m-d');
            
            // Get ALL launched events
            $events = Event::where('is_launched', true)
                ->orderBy('event_date', 'asc')
                ->orderBy('event_time', 'asc')
                ->get();

            // Get events that are launched and happening today
            $todayEvents = $events->filter(function($event) use ($today) {
                return $event->event_date->format('Y-m-d') === $today;
            });

            // Get upcoming launched events (future dates)
            $upcomingEvents = $events->filter(function($event) use ($today) {
                return $event->event_date->format('Y-m-d') > $today;
            });

            Log::info('User events page loaded', [
                'all_events' => $events->count(),
                'today_events' => $todayEvents->count(),
                'upcoming_events' => $upcomingEvents->count()
            ]);

            return view('eventpage', compact('events', 'todayEvents', 'upcomingEvents'));

        } catch (\Exception $e) {
            Log::error('Error loading user events: ' . $e->getMessage());
            return view('eventpage', [
                'events' => collect(),
                'todayEvents' => collect(),
                'upcomingEvents' => collect()
            ]);
        }
    }

    /**
     * Display events for public viewing (launched events only)
     */
    public function publicIndex(): View
    {
        try {
            $today = now()->format('Y-m-d');
            
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

    public function generateQRCode($id): JsonResponse
{
    try {
        $eventId = (int)$id;
        $event = Event::find($eventId);
        
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
}