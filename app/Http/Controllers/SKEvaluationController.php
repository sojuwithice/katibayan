<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\EvaluationQuestion;

class SKEvaluationController extends Controller
{
    /**
     * Show evaluation feedback overview
     */
    public function index()
{
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    // Calculate age
    $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

    // Role badge
    $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

    // Get events with evaluations
    $eventsWithEvaluations = Event::where('barangay_id', $user->barangay_id)
        ->whereHas('evaluations')
        ->withCount('evaluations')
        ->with(['evaluations' => function($query) {
            $query->latest();
        }])
        ->get();

    // Add "locked" attribute for each event
    $eventsWithEvaluations->transform(function($event) {
        // Lock if event date is before today
        $event->locked = Carbon::parse($event->event_date)->lt(Carbon::today());
        return $event;
    });

    // Get all evaluation questions
$questions = EvaluationQuestion::all();

// Check if any question has evaluations to lock them
foreach ($questions as $question) {
    $question->evaluations_count = $question->evaluations()->count();
}

    return view('sk-evaluation-feedback', compact(
        'eventsWithEvaluations', 
        'user', 
        'age', 
        'roleBadge',
        'questions'
    ));
}


    /**
 * Show detailed evaluation review for a specific event
 */
public function showReview($eventId)
{
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    // Calculate age from date_of_birth
    $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

    // Determine role badge based on actual enum values
    $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

    // Get event from the same barangay as the logged in user
    $event = Event::where('barangay_id', $user->barangay_id)
        ->with(['evaluations.user', 'evaluations' => function($query) {
            $query->latest();
        }])
        ->findOrFail($eventId);

    // Get all evaluation questions
    $questions = EvaluationQuestion::where('is_active', true)
        ->orderBy('order')
        ->get();

    // If no active questions, get all questions
    if ($questions->isEmpty()) {
        $questions = EvaluationQuestion::orderBy('order')->get();
    }

    // Calculate statistics
    $totalEvaluations = $event->evaluations->count();
    $averageRatings = [];
    $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $overallAverage = 0;
    
    if ($totalEvaluations > 0) {
        // Calculate averages for each question by ID
        foreach ($questions as $question) {
            $sum = 0;
            $count = 0;
            
            foreach ($event->evaluations as $evaluation) {
                $ratings = json_decode($evaluation->ratings, true) ?? [];
                
                if (isset($ratings[$question->id])) {
                    $rating = $ratings[$question->id];
                    $sum += $rating;
                    $count++;
                    
                    // Update rating distribution
                    if ($rating >= 1 && $rating <= 5) {
                        $ratingDistribution[$rating]++;
                    }
                }
            }
            
            $averageRatings[$question->id] = $count > 0 ? $sum / $count : 0;
        }
        
        // Calculate overall average
        if (!empty($averageRatings)) {
            $overallAverage = array_sum($averageRatings) / count($averageRatings);
        }
        
        // For backward compatibility with q1, q2 format
        $averageRatingsByNumber = [];
        foreach ($questions as $index => $question) {
            $questionNumber = $index + 1;
            $averageRatingsByNumber['q' . $questionNumber] = $averageRatings[$question->id] ?? 0;
        }
        
        // Also keep the old calculation for q1-q7 if you want
        $legacyAverageRatings = [];
        for ($i = 1; $i <= 7; $i++) {
            $questionKey = 'q' . $i;
            $legacyAverageRatings[$questionKey] = $event->evaluations->avg(function($eval) use ($questionKey) {
                $ratings = json_decode($eval->ratings, true);
                return $ratings[$questionKey] ?? 0;
            });
        }
    }

    return view('sk-eval-review', compact(
        'event', 
        'totalEvaluations', 
        'averageRatings', 
        'averageRatingsByNumber',
        'overallAverage',
        'ratingDistribution',
        'questions', // ADD THIS LINE - THIS IS WHAT'S MISSING!
        'user', 
        'age', 
        'roleBadge'
    ));
}


    public function saveQuestions(Request $request)
{
    $questions = $request->input('questions', []);
    
    try {
        foreach ($questions as $questionData) {
            if (str_starts_with($questionData['id'], 'new-')) {
                // Create new question
                EvaluationQuestion::create([
                    'question_text' => $questionData['question_text'],
                    'is_default' => false,
                    'order' => $questionData['order']
                ]);
            } else {
                // Update existing question
                $question = EvaluationQuestion::find($questionData['id']);
                
                if ($question) {
                    // Only update if not locked (no evaluations or is default)
                    if ($question->is_default || $question->evaluations_count == 0) {
                        $question->update([
                            'question_text' => $questionData['question_text'],
                            'order' => $questionData['order']
                        ]);
                    }
                }
            }
        }
        
        // Get updated questions list
        $updatedQuestions = EvaluationQuestion::orderBy('order')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Questions saved successfully!',
            'questions' => $updatedQuestions
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error saving questions: ' . $e->getMessage()
        ], 500);
    }
}

// Sa EvaluationQuestionController.php
public function restoreDefault(Request $request) // Changed from restoreDefaultQuestions to restoreDefault
{
    try {
        DB::beginTransaction();
        
        // 1. Delete all current questions
        EvaluationQuestion::query()->delete();
        
        // 2. Use the same default questions from seeder
        $defaultQuestions = [
            ['question_text' => 'Was the purpose of the program/event explained clearly?', 'order' => 1, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Was the time given for the program/event enough?', 'order' => 2, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Were you able to join and participate in the activities?', 'order' => 3, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did you learn something new from this program/event?', 'order' => 4, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did the SK officials/facilitators treat all participants fairly and equally?', 'order' => 5, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did the SK officials/facilitators show enthusiasm and commitment in leading the program/event?', 'order' => 6, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Overall, are you satisfied with this program/event?', 'order' => 7, 'is_default' => true, 'is_active' => true],
        ];
        
        // 3. Insert default questions
        $questions = [];
        foreach ($defaultQuestions as $questionData) {
            $questions[] = array_merge($questionData, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        EvaluationQuestion::insert($questions);
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Default questions restored successfully',
            'count' => count($defaultQuestions)
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Failed to restore default questions: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => 'Failed to restore default questions: ' . $e->getMessage()
        ], 500);
    }
}

}