<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Program;
use App\Models\ProgramRegistration;
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
     * Show evaluation page with attended events AND programs
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get events that user attended - FIXED: Remove status check
        $attendedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->with(['evaluations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('event_date', 'desc')
        ->get();

        // Get programs that user registered for - FIXED: Remove status check
        $registeredPrograms = Program::whereHas('programRegistrations', function($query) use ($user) {
            $query->where('user_id', $user->id);
            // Remove the status condition since the column doesn't exist
            // ->where('status', 'registered');
        })
        ->with(['evaluations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('event_date', 'desc')
        ->get();

        // Combine events and programs for the view
        $allActivities = [
            'events' => $attendedEvents,
            'programs' => $registeredPrograms
        ];

        // Calculate role badge and age
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        // Get notification count for unevaluated activities
        $unevaluatedActivities = $this->getUnevaluatedActivities($user);
        $notificationCount = count($unevaluatedActivities);

        return view('evaluationpage', compact('allActivities', 'user', 'age', 'roleBadge', 'notificationCount', 'unevaluatedActivities'));
    }

    /**
     * Get unevaluated activities for notifications
     */
    private function getUnevaluatedActivities($user)
    {
        $unevaluatedActivities = [];

        // Get unevaluated events
        $unevaluatedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->whereDoesntHave('evaluations', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();

        foreach ($unevaluatedEvents as $event) {
            $attendance = $event->attendances()->where('user_id', $user->id)->first();
            $unevaluatedActivities[] = [
                'id' => $event->id,
                'type' => 'event',
                'title' => $event->title,
                'date' => $attendance->attended_at ?? $event->event_date
            ];
        }

        // Get unevaluated programs - FIXED: Remove status check
        $unevaluatedPrograms = Program::whereHas('programRegistrations', function($query) use ($user) {
            $query->where('user_id', $user->id);
            // Remove status condition
        })
        ->whereDoesntHave('evaluations', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();

        foreach ($unevaluatedPrograms as $program) {
            $registration = $program->programRegistrations()->where('user_id', $user->id)->first();
            $unevaluatedActivities[] = [
                'id' => $program->id,
                'type' => 'program',
                'title' => $program->title,
                'date' => $registration->created_at ?? $program->event_date
            ];
        }

        return $unevaluatedActivities;
    }

    /**
     * Store evaluation for an event OR program
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'event_id' => 'nullable|exists:events,id',
                'program_id' => 'nullable|exists:programs,id',
                'ratings' => 'required|array',
                'ratings.*' => 'required|integer|min:1|max:5',
                'comments' => 'nullable|string|max:1000',
            ]);

            // Validate that either event_id or program_id is provided
            if (empty($validated['event_id']) && empty($validated['program_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Either event_id or program_id is required'
                ], 422);
            }

            $activity = null;
            $activityType = null;

            if (!empty($validated['event_id'])) {
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

                $activity = Event::find($validated['event_id']);
                $activityType = 'event';
            } else {
                // Check if user registered for this program - FIXED: Remove status check
                $registration = ProgramRegistration::where('user_id', $user->id)
                    ->where('program_id', $validated['program_id'])
                    // Remove status condition
                    ->first();

                if (!$registration) {
                    return response()->json([
                        'success' => false,
                        'error' => 'You are not registered for this program'
                    ], 403);
                }

                $activity = Program::find($validated['program_id']);
                $activityType = 'program';
            }

            // Check if already evaluated
            $existingEvaluation = Evaluation::where('user_id', $user->id)
                ->where(function($query) use ($validated) {
                    if (!empty($validated['event_id'])) {
                        $query->where('event_id', $validated['event_id']);
                    } else {
                        $query->where('program_id', $validated['program_id']);
                    }
                })
                ->first();

            if ($existingEvaluation) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have already evaluated this activity'
                ], 409);
            }

            // Create evaluation
            $evaluation = Evaluation::create([
                'user_id' => $user->id,
                'event_id' => $validated['event_id'] ?? null,
                'program_id' => $validated['program_id'] ?? null,
                'ratings' => json_encode($validated['ratings']),
                'comments' => $validated['comments'] ?? null,
                'submitted_at' => now(),
            ]);

            // Create notifications for SK users in the same barangay
            $this->createEvaluationNotification($user, $activity, $evaluation, $activityType);

            Log::info("Evaluation submitted for {$activityType} {$activity->id} by user {$user->id}");

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
     * Create notification for SK users when KK evaluates an event or program
     */
    private function createEvaluationNotification($kkUser, $activity, $evaluation, $activityType)
    {
        try {
            $skUsers = User::where('barangay_id', $kkUser->barangay_id)
                          ->where('role', 'sk')
                          ->where('account_status', 'approved')
                          ->get();

            $activityTypeText = $activityType === 'event' ? 'event' : 'program';

            foreach ($skUsers as $skUser) {
                Notification::create([
                    'user_id' => $skUser->id,
                    'evaluation_id' => $evaluation->id,
                    'type' => 'evaluation_submitted',
                    'message' => "{$kkUser->given_name} {$kkUser->last_name} evaluated the {$activityTypeText} \"{$activity->title}\"",
                    'is_read' => false,
                ]);
            }

            Log::info("Created evaluation notifications for SK users in barangay {$kkUser->barangay_id}");

        } catch (\Exception $e) {
            Log::error('Error creating evaluation notification: ' . $e->getMessage());
        }
    }

    /**
     * Check if user has evaluated an event or program
     */
    public function checkEvaluation(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'event_id' => 'nullable|exists:events,id',
                'program_id' => 'nullable|exists:programs,id',
            ]);

            $evaluated = Evaluation::where('user_id', $user->id)
                ->where(function($query) use ($validated) {
                    if (!empty($validated['event_id'])) {
                        $query->where('event_id', $validated['event_id']);
                    } else {
                        $query->where('program_id', $validated['program_id']);
                    }
                })
                ->exists();

            return response()->json([
                'evaluated' => $evaluated
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking evaluation: ' . $e->getMessage());
            return response()->json(['evaluated' => false]);
        }
    }

    /**
     * Show a specific evaluation by redirecting to the index with an anchor.
     */
    public function show($id)
    {
        $user = Auth::user();

        // Try to find as event first
        $event = Event::where('id', $id)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('attended_at');
            })
            ->first();

        if ($event) {
            return redirect()->route('evaluation', ['#' => 'event-card-' . $event->id]);
        }

        // If not event, try as program - FIXED: Remove status check
        $program = Program::where('id', $id)
            ->whereHas('programRegistrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
                // Remove status condition
            })
            ->first();

        if ($program) {
            return redirect()->route('evaluation', ['#' => 'program-card-' . $program->id]);
        }

        abort(404, 'Activity not found or you did not participate');
    }

    /**
     * Get certificates for evaluated events AND programs
     */
    public function getCertificates(): JsonResponse
    {
        try {
            $user = Auth::user();
            $userId = $user->id;

            Log::info("Fetching certificates for user: {$userId}, Barangay: {$user->barangay_id}");

            // Get evaluated events
            $evaluatedEvents = Event::select(
                    'events.id', 
                    'events.title',
                    'events.event_date',
                    'events.image',
                    'events.barangay_id',
                    'certificate_requests.status as request_status',
                    'certificate_requests.request_count',
                    'certificate_requests.updated_at as request_updated_at'
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

                    $canRequestAgain = false;
                    $requestCount = $event->request_count ?? 0;

                    if ($event->request_status === null) {
                        $canRequestAgain = true;
                    } else if ($event->request_status !== 'claimed' && $requestCount < 2) {
                        $lastRequestTime = Carbon::parse($event->request_updated_at);
                        if ($lastRequestTime->isBefore(Carbon::now()->subDays(7))) {
                            $canRequestAgain = true;
                        }
                    }

                    return [
                        'activity_type' => 'event',
                        'activity_id' => $event->id,
                        'activity_title' => $event->title,
                        'activity_date' => $eventDate,
                        'activity_image' => $eventImage,
                        'evaluated_at' => $evaluatedAt,
                        'evaluation_date' => $evaluationDate,
                        'request_status' => $event->request_status,
                        'request_count' => $requestCount,
                        'can_request_again' => $canRequestAgain
                    ];
                });

            // Get evaluated programs
            $evaluatedPrograms = Program::select(
                    'programs.id', 
                    'programs.title',
                    'programs.event_date',
                    'programs.display_image as image',
                    'programs.barangay_id',
                    'certificate_requests.status as request_status',
                    'certificate_requests.request_count',
                    'certificate_requests.updated_at as request_updated_at'
                )
                ->whereHas('evaluations', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->leftJoin('certificate_requests', function($join) use ($userId) {
                    $join->on('programs.id', '=', 'certificate_requests.program_id')
                         ->where('certificate_requests.user_id', '=', $userId);
                })
                ->with(['evaluations' => function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->select('id', 'program_id', 'submitted_at', 'created_at');
                }])
                ->where('programs.barangay_id', $user->barangay_id)
                ->get()
                ->map(function ($program) {
                    $evaluation = $program->evaluations->first();
                    
                    // Safely handle event_date
                    $programDate = 'Date not available';
                    if ($program->event_date) {
                        try {
                            $programDate = $program->event_date instanceof Carbon ? $program->event_date->format('F d, Y') : Carbon::parse($program->event_date)->format('F d, Y');
                        } catch (\Exception $e) { 
                            Log::error("Error parsing program date for program {$program->id}: " . $e->getMessage());
                        }
                    }
                    
                    // Safely handle evaluated_at
                    $evaluatedAt = $evaluation?->submitted_at ?? $evaluation?->created_at ?? now();
                    $evaluationDate = $evaluatedAt instanceof Carbon ? $evaluatedAt->format('F d, Y') : Carbon::parse($evaluatedAt)->format('F d, Y');
                    
                    // Build image URL safely
                    $programImage = null;
                    if ($program->image) {
                        try {
                            if (Storage::disk('public')->exists($program->image)) {
                                $programImage = asset('storage/' . $program->image);
                            } else { 
                                Log::warning("Program image not found: " . $program->image);
                            }
                        } catch (\Exception $e) { 
                            Log::error("Error generating image URL for program {$program->id}: " . $e->getMessage());
                        }
                    }

                    $canRequestAgain = false;
                    $requestCount = $program->request_count ?? 0;

                    if ($program->request_status === null) {
                        $canRequestAgain = true;
                    } else if ($program->request_status !== 'claimed' && $requestCount < 2) {
                        $lastRequestTime = Carbon::parse($program->request_updated_at);
                        if ($lastRequestTime->isBefore(Carbon::now()->subDays(7))) {
                            $canRequestAgain = true;
                        }
                    }

                    return [
                        'activity_type' => 'program',
                        'activity_id' => $program->id,
                        'activity_title' => $program->title,
                        'activity_date' => $programDate,
                        'activity_image' => $programImage,
                        'evaluated_at' => $evaluatedAt,
                        'evaluation_date' => $evaluationDate,
                        'request_status' => $program->request_status,
                        'request_count' => $requestCount,
                        'can_request_again' => $canRequestAgain
                    ];
                });

            // Combine events and programs
            $allCertificates = $evaluatedEvents->merge($evaluatedPrograms)->sortByDesc('evaluated_at');

            Log::info("Found {$allCertificates->count()} certificates for user {$userId}");

            return response()->json([
                'success' => true,
                'certificates' => $allCertificates,
                'total_count' => $allCertificates->count()
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