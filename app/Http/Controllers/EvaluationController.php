<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EvaluationController extends Controller
{
    /**
     * Show evaluation page with attended events
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get events that user attended
        $attendedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->with(['evaluations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->get();

        // Calculate age if needed
        $age = $user->birthday ? now()->diffInYears($user->birthday) : null;
        
        // Role badge
        $roleBadge = $user->role ?? 'GUEST';

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
}