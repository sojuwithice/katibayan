<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
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

        // Calculate role badge and age for the user
        $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';
        $age = $user && $user->date_of_birth 
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

    /**
     * Get certificates for evaluated events
     */
    public function getCertificates(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            Log::info("Fetching certificates for user: {$user->id}, Barangay: {$user->barangay_id}");

            // Get events that user has evaluated
            $evaluatedEvents = Event::whereHas('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['evaluations' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->select('id', 'event_id', 'submitted_at', 'created_at');
            }])
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('event_date', 'desc')
            ->get()
            ->map(function ($event) {
                $evaluation = $event->evaluations->first();
                
                // Safely handle event_date
                $eventDate = 'Date not available';
                if ($event->event_date) {
                    try {
                        $eventDate = $event->event_date instanceof Carbon 
                            ? $event->event_date->format('F d, Y')
                            : Carbon::parse($event->event_date)->format('F d, Y');
                    } catch (\Exception $e) {
                        Log::error("Error parsing event date for event {$event->id}: " . $e->getMessage());
                    }
                }
                
                // Safely handle evaluated_at
                $evaluatedAt = $evaluation->submitted_at ?? $evaluation->created_at ?? now();
                $evaluationDate = $evaluatedAt instanceof Carbon 
                    ? $evaluatedAt->format('F d, Y')
                    : Carbon::parse($evaluatedAt)->format('F d, Y');
                
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

                return [
                    'event_id' => $event->id,
                    'event_title' => $event->title,
                    'event_date' => $eventDate,
                    'event_image' => $eventImage,
                    'evaluated_at' => $evaluatedAt,
                    'evaluation_date' => $evaluationDate
                ];
            });

            Log::info("Found {$evaluatedEvents->count()} certificates for user {$user->id}");

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