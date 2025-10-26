<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EvaluationController extends Controller
{
    /**
     * Show certificate page
     */
    public function certificatePage()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate role badge and age for the user - FIXED
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('certificatepage', compact('user', 'roleBadge', 'age'));
    }

    /**
     * Show evaluation page with attended events
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get events that user attended
        $attendedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->with(['evaluations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('event_date', 'desc') // In-order ko na
        ->get();

        // Calculate role badge and age - FIXED
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('evaluationpage', compact('attendedEvents', 'user', 'age', 'roleBadge'));
    }

    /**
     * Store evaluation for an event
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'ratings' => 'required|array',
                'ratings.*' => 'required|integer|min:1|max:5',
                'comments' => 'nullable|string|max:1000',
            ]);

            // Check if user attended this event
            $attendance = Attendance::where('user_id', $user->id)
                ->where('event_id', $validated['event_id'])
                ->whereNotNull('attended_at')
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have not attended this event'
                ], 403);
            }

            // Check if already evaluated
            $existingEvaluation = Evaluation::where('user_id', $user->id)
                ->where('event_id', $validated['event_id'])
                ->first();

            if ($existingEvaluation) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have already evaluated this event'
                ], 409);
            }

            // Create evaluation
            $evaluation = Evaluation::create([
                'user_id' => $user->id,
                'event_id' => $validated['event_id'],
                'ratings' => json_encode($validated['ratings']),
                'comments' => $validated['comments'] ?? null,
                'submitted_at' => now(),
            ]);

            // Get event details
            $event = Event::find($validated['event_id']);
            
            // Create notifications for SK users in the same barangay
            $this->createEvaluationNotification($user, $event, $evaluation);

            Log::info("Evaluation submitted for event {$validated['event_id']} by user {$user->id}");

            return response()->json([
                'success' => true,
                'message' => 'Evaluation submitted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing evaluation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to submit evaluation'
            ], 500);
        }
    }

    /**
     * Create notification for SK users when KK evaluates an event
     */
    private function createEvaluationNotification($kkUser, $event, $evaluation)
    {
        try {
         
            $skUsers = User::where('barangay_id', $kkUser->barangay_id)
                          ->where('role', 'sk')
                          ->where('account_status', 'approved')
                          ->get();

            foreach ($skUsers as $skUser) {
                Notification::create([
                    'user_id' => $skUser->id,
                    'evaluation_id' => $evaluation->id,
                    'type' => 'evaluation_submitted',
                    'message' => "{$kkUser->given_name} {$kkUser->last_name} evaluated the event \"{$event->title}\"",
                    'is_read' => false,
                ]);
            }

            Log::info("Created evaluation notifications for SK users in barangay {$kkUser->barangay_id}");

        } catch (\Exception $e) {
            Log::error('Error creating evaluation notification: ' . $e->getMessage());
        }
    }

    /**
     * Check if user has evaluated an event
     */
    public function checkEvaluation($eventId)
    {
        try {
            $user = Auth::user();
            
            $evaluated = Evaluation::where('user_id', $user->id)
                ->where('event_id', $eventId)
                ->exists();

            return response()->json([
                'evaluated' => $evaluated
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking evaluation: ' . $e->getMessage());
            return response()->json(['evaluated' => false]);
        }
    }

    // --- ITO YUNG BAGONG DAGDAG ---
    /**
     * Show a specific evaluation by redirecting to the index with an anchor.
     * Ito ang gagamitin ng route('evaluation.show', $event->id)
     */
    public function show($id)
    {
        $user = Auth::user();

        // 1. Tiyakin na may event at umattend ang user
        $event = Event::where('id', $id)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('attended_at');
            })
            ->firstOrFail(); // Mag-404 kung walang event o hindi umattend

        // 2. I-redirect sa main evaluation page ('evaluation'), pero may anchor link (#)
        // Ito ay mag-jujump sa page papunta mismo sa event card na 'yon
        return redirect()->route('evaluation', ['#' => 'event-card-' . $event->id]);
    }
    // --- END NG BAGONG DAGDAG ---


    /**
     * Get certificates for evaluated events
     */
    public function getCertificates(): JsonResponse
    {
        try {
            $user = Auth::user();
            $userId = $user->id;

            Log::info("Fetching certificates for user: {$userId}, Barangay: {$user->barangay_id}");

            // (BINAGO) Idinagdag natin 'yung 'request_count' at 'updated_at'
            $evaluatedEvents = Event::select(
                    'events.id', 
                    'events.title',
                    'events.event_date',
                    'events.image',
                    'events.barangay_id',
                    'certificate_requests.status as request_status',
                    'certificate_requests.request_count',     // <-- BAGO
                    'certificate_requests.updated_at as request_updated_at' // <-- BAGO
                )
                ->whereHas('evaluations', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->leftJoin('certificate_requests', function($join) use ($userId) {
                    $join->on('events.id', '=', 'certificate_requests.event_id')
                         ->where('certificate_requests.user_id', '=', $userId);
                })
                ->with(['evaluations' => function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->select('id', 'event_id', 'submitted_at', 'created_at');
                }])
                ->where('events.barangay_id', $user->barangay_id)
                ->orderBy('events.event_date', 'desc')
                ->get()
                ->map(function ($event) {
                    $evaluation = $event->evaluations->first();
                    
                    // Safely handle event_date
                    $eventDate = 'Date not available';
                    if ($event->event_date) {
                        try {
                            $eventDate = $event->event_date instanceof Carbon ? $event->event_date->format('F d, Y') : Carbon::parse($event->event_date)->format('F d, Y');
                        } catch (\Exception $e) { 
                            Log::error("Error parsing event date for event {$event->id}: " . $e->getMessage());
                        }
                    }
                    
                    // Safely handle evaluated_at
                    $evaluatedAt = $evaluation?->submitted_at ?? $evaluation?->created_at ?? now();
                    $evaluationDate = $evaluatedAt instanceof Carbon ? $evaluatedAt->format('F d, Y') : Carbon::parse($evaluatedAt)->format('F d, Y');
                    
                    // Build image URL safely
                    $eventImage = null;
                    if ($event->image) {
                        try {
                            if (Storage::disk('public')->exists($event->image)) {
                                $eventImage = asset('storage/' . $event->image);
                            } else { 
                                Log::warning("Event image not found: " . $event->image);
                            }
                        } catch (\Exception $e) { 
                            Log::error("Error generating image URL for event {$event->id}: " . $e->getMessage());
                        }
                    }

                    // (BAGO) Logic para malaman kung pwede na ulit mag-request
                    $canRequestAgain = false;
                    $requestCount = $event->request_count ?? 0;

                    if ($event->request_status === null) {
                        // 1. Wala pang request (1st time)
                        $canRequestAgain = true;
                    } else if ($event->request_status !== 'claimed' && $requestCount < 2) {
                        // 2. Hindi pa claimed AT wala pa sa 2-request limit
                        $lastRequestTime = Carbon::parse($event->request_updated_at);
                        if ($lastRequestTime->isBefore(Carbon::now()->subDays(7))) {
                            // 3. At nakalipas na ang 7-day cooldown
                            $canRequestAgain = true;
                        }
                    }
                    // Kung claimed na, o nasa cooldown pa, o max na, $canRequestAgain ay mananatiling 'false'

                    return [
                        'event_id' => $event->id,
                        'event_title' => $event->title,
                        'event_date' => $eventDate,
                        'event_image' => $eventImage,
                        'evaluated_at' => $evaluatedAt,
                        'evaluation_date' => $evaluationDate,
                        'request_status' => $event->request_status,
                        'request_count' => $requestCount,     // <-- BAGO
                        'can_request_again' => $canRequestAgain   // <-- BAGO
                    ];
                });

            Log::info("Found {$evaluatedEvents->count()} certificates for user {$userId}");

            return response()->json([
                'success' => true,
                'certificates' => $evaluatedEvents,
                'total_count' => $evaluatedEvents->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching certificates: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load certificates',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}