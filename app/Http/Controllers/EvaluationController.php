<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationQuestion;


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

        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('certificatepage', compact('user', 'roleBadge', 'age'));
    }

    /**
     * Show evaluation page with dashboard-like notifications
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // --- Get notifications (same as dashboard) ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // --- Get attended events and programs ---
        $attendedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->with(['evaluations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('event_date', 'desc')
        ->get();

        $registeredPrograms = Program::whereHas('programRegistrations', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['evaluations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('event_date', 'desc')
        ->get();

        // --- Unevaluated activities (for notifications) ---
        $unevaluatedEvents = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        $unevaluatedPrograms = Program::where('barangay_id', $user->barangay_id)
            ->whereHas('programRegistrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['programRegistrations' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        $unevaluatedActivities = collect();
        
        foreach ($unevaluatedEvents as $event) {
            $unevaluatedActivities->push([
                'id' => $event->id,
                'type' => 'event',
                'title' => $event->title,
                'attendance' => $event->attendances->first(),
                'created_at' => $event->attendances->first()->created_at ?? $event->created_at
            ]);
        }
        
        foreach ($unevaluatedPrograms as $program) {
            $unevaluatedActivities->push([
                'id' => $program->id,
                'type' => 'program',
                'title' => $program->title,
                'registration' => $program->programRegistrations->first(),
                'created_at' => $program->programRegistrations->first()->created_at ?? $program->created_at
            ]);
        }

        // Calculate total notification count
        $totalNotificationCount = $unreadNotificationCount + $unevaluatedActivities->count();

        // --- Combine data for view ---
        $allActivities = [
            'events' => $attendedEvents,
            'programs' => $registeredPrograms
        ];

        // Calculate role badge and age
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        // Get announcements
        $announcements = Announcement::where('barangay_id', $user->barangay_id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->get();

        $evaluationQuestions = EvaluationQuestion::where('is_active',1)
        ->orderBy('order')
        ->get();


        return view('evaluationpage', compact(
            'allActivities',
            'user',
            'age',
            'roleBadge',
            'generalNotifications',
            'unevaluatedActivities',
            'totalNotificationCount',
            'announcements',
            'evaluationQuestions'
        ));
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
                // Check if user registered for this program
                $registration = ProgramRegistration::where('user_id', $user->id)
                    ->where('program_id', $validated['program_id'])
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

            // Create notifications for SK users
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
     * Create notification for SK users
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
                    'title' => 'New Evaluation Submitted',
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

        // If not event, try as program
        $program = Program::where('id', $id)
            ->whereHas('programRegistrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if ($program) {
            return redirect()->route('evaluation', ['#' => 'program-card-' . $program->id]);
        }

        abort(404, 'Activity not found or you did not participate');
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            $notification = Notification::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if ($notification) {
                $notification->update(['is_read' => true]);
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'error' => 'Notification not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
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
                ->get()
                ->map(function ($event) {
                    $evaluation = $event->evaluations->first();
                    $eventDate = 'Date not available';
                    if ($event->event_date) {
                        try {
                            $eventDate = $event->event_date instanceof Carbon ? $event->event_date->format('F d, Y') : Carbon::parse($event->event_date)->format('F d, Y');
                        } catch (\Exception $e) { Log::error("Error parsing event date: " . $e->getMessage()); }
                    }
                    $evaluatedAt = $evaluation?->submitted_at ?? $evaluation?->created_at ?? now();
                    $eventImage = null;
                    if ($event->image) {
                        try {
                            if (Storage::disk('public')->exists($event->image)) {
                                $eventImage = asset('storage/' . $event->image);
                            } else { Log::warning("Event image not found: " . $event->image); }
                        } catch (\Exception $e) { Log::error("Error generating image URL: " . $e->getMessage()); }
                    }
                    $canRequestAgain = false;
                    $requestCount = $event->request_count ?? 0;
                    if ($event->request_status === null) {
                        $canRequestAgain = true;
                    } else if ($event->request_status !== 'claimed' && $requestCount < 2) {
                        if ($event->request_updated_at) {
                            $lastRequestTime = Carbon::parse($event->request_updated_at);
                            if ($lastRequestTime->isBefore(Carbon::now()->subDays(7))) {
                                $canRequestAgain = true;
                            }
                        }
                    }
                    return [
                        'event_id' => $event->id,
                        'program_id' => null,
                        'event_title' => $event->title,
                        'event_date' => $eventDate,
                        'event_image' => $eventImage,
                        'evaluated_at' => $evaluatedAt,
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
                ->get()
                ->map(function ($program) {
                    $evaluation = $program->evaluations->first();
                    $programDate = 'Date not available';
                    if ($program->event_date) {
                        try {
                            $programDate = $program->event_date instanceof Carbon ? $program->event_date->format('F d, Y') : Carbon::parse($program->event_date)->format('F d, Y');
                        } catch (\Exception $e) { Log::error("Error parsing program date: " . $e->getMessage()); }
                    }
                    $evaluatedAt = $evaluation?->submitted_at ?? $evaluation?->created_at ?? now();
                    $programImage = null;
                    if ($program->image) {
                        try {
                            if (Storage::disk('public')->exists($program->image)) {
                                $programImage = asset('storage/' . $program->image);
                            } else { Log::warning("Program image not found: " . $program->image); }
                        } catch (\Exception $e) { Log::error("Error generating image URL: " . $e->getMessage()); }
                    }
                    
                    $canRequestAgain = false;
                    $requestCount = $program->request_count ?? 0;
                    if ($program->request_status === null) {
                        $canRequestAgain = true;
                    } else if ($program->request_status !== 'claimed' && $requestCount < 2) {
                        if ($program->request_updated_at) {
                            $lastRequestTime = Carbon::parse($program->request_updated_at);
                            if ($lastRequestTime->isBefore(Carbon::now()->subDays(7))) {
                                $canRequestAgain = true;
                            }
                        }
                    }

                    return [
                        'event_id' => null,
                        'program_id' => $program->id,
                        'event_title' => $program->title,
                        'event_date' => $programDate,
                        'event_image' => $programImage,
                        'evaluated_at' => $evaluatedAt,
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
                'certificates' => $allCertificates->values(),
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