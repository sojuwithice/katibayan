<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Suggestion;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SuggestionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('loginpage');
        }

        // Calculate age
        $age = 'N/A';
        if ($user->date_of_birth) {
            try {
                $age = Carbon::parse($user->date_of_birth)->age;
            } catch (\Exception $e) {
                $age = 'N/A';
            }
        }

        $roleBadge = strtoupper($user->role) . '-Member';

        // --- NOTIFICATIONS SECTION (SAME AS DASHBOARD AND POLLS) ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // Count attended events
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count registered programs
        $registeredProgramsCount = ProgramRegistration::where('user_id', $user->id)
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total activities (events + programs)
        $totalActivities = $attendedEventsCount + $registeredProgramsCount;

        // Count evaluated events
        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('event_id')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count evaluated programs
        $evaluatedProgramsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('program_id')
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total evaluated activities
        $evaluatedActivities = $evaluatedEventsCount + $evaluatedProgramsCount;

        // Calculate activities that need evaluation
        $activitiesToEvaluate = $totalActivities - $evaluatedActivities;
        $activitiesToEvaluate = max(0, $activitiesToEvaluate);

        // Get unevaluated events for notifications
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

        // Get unevaluated programs for notifications
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

        // Prepare unevaluated activities for display
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

        // --- Total Notification Count for Badge ---
        $totalNotificationCount = $unreadNotificationCount + $unevaluatedActivities->count();

        return view('suggestionbox', compact(
            'user', 
            'age', 
            'roleBadge',
            'generalNotifications',
            'unevaluatedActivities',
            'totalNotificationCount'
        ));
    }

   public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'suggestion_type' => 'required|in:event,program,others',
        'suggestions' => 'required|string|min:10|max:1000',
        'is_anonymous' => 'sometimes|boolean'
    ]);

    try {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to submit a suggestion.'
            ], 401);
        }

        $user = Auth::user();

        $suggestion = Suggestion::create([
            'user_id' => $validated['is_anonymous'] ?? false ? null : $user->id,
            'committee' => $validated['suggestion_type'], // Using committee field to store suggestion type
            'suggestions' => $validated['suggestions'],
            'barangay_id' => $user->barangay_id,
            'is_anonymous' => $validated['is_anonymous'] ?? false
        ]);

        Log::info('Suggestion submitted successfully', [
            'suggestion_id' => $suggestion->id,
            'suggestion_type' => $validated['suggestion_type'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'barangay_id' => $user->barangay_id
        ]);

        return response()->json([
            'success' => true,
            'message' => $validated['is_anonymous'] ?? false 
                ? 'Anonymous suggestion submitted successfully!' 
                : 'Suggestion submitted successfully!'
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to submit suggestion', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to submit suggestion: ' . $e->getMessage()
        ], 500);
    }
}
    public function youthSuggestion()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('loginpage');
        }

        // Calculate age
        $age = 'N/A';
        if ($user->date_of_birth) {
            try {
                $age = Carbon::parse($user->date_of_birth)->age;
            } catch (\Exception $e) {
                $age = 'N/A';
            }
        }

        $roleBadge = strtoupper($user->role) . '-Member';

        // --- NOTIFICATIONS SECTION (SAME AS DASHBOARD AND POLLS) ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // Count attended events
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count registered programs
        $registeredProgramsCount = ProgramRegistration::where('user_id', $user->id)
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total activities (events + programs)
        $totalActivities = $attendedEventsCount + $registeredProgramsCount;

        // Count evaluated events
        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('event_id')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count evaluated programs
        $evaluatedProgramsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('program_id')
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total evaluated activities
        $evaluatedActivities = $evaluatedEventsCount + $evaluatedProgramsCount;

        // Calculate activities that need evaluation
        $activitiesToEvaluate = $totalActivities - $evaluatedActivities;
        $activitiesToEvaluate = max(0, $activitiesToEvaluate);

        // Get unevaluated events for notifications
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

        // Get unevaluated programs for notifications
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

        // Prepare unevaluated activities for display
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

        // --- Total Notification Count for Badge ---
        $totalNotificationCount = $unreadNotificationCount + $unevaluatedActivities->count();

        return view('youth-suggestion', compact(
            'user', 
            'age', 
            'roleBadge',
            'generalNotifications',
            'unevaluatedActivities',
            'totalNotificationCount'
        ));
    }

    public function getSKSuggestions()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Get suggestions from the same barangay
            $suggestions = Suggestion::with('user')
                ->where('barangay_id', $user->barangay_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch suggestions', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch suggestions: ' . $e->getMessage()
            ], 500);
        }
    }
}