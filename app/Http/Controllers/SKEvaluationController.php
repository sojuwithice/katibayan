<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // Calculate age from date_of_birth
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

        // Determine role badge based on actual enum values
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        $eventsWithEvaluations = Event::whereHas('evaluations')
            ->withCount('evaluations')
            ->with(['evaluations' => function($query) {
                $query->latest();
            }])
            ->get();

        return view('sk-evaluation-feedback', compact('eventsWithEvaluations', 'user', 'age', 'roleBadge'));
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

        $event = Event::with(['evaluations.user', 'evaluations' => function($query) {
            $query->latest();
        }])->findOrFail($eventId);

        // Calculate statistics
        $totalEvaluations = $event->evaluations->count();
        $averageRatings = [];
        
        // Calculate average for each question
        for ($i = 1; $i <= 7; $i++) {
            $questionKey = 'q' . $i;
            $averageRatings[$questionKey] = $event->evaluations->avg(function($eval) use ($questionKey) {
                $ratings = json_decode($eval->ratings, true);
                return $ratings[$questionKey] ?? 0;
            });
        }

        // Calculate overall average
        $overallAverage = array_sum($averageRatings) / count($averageRatings);

        // Rating distribution
        $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($event->evaluations as $evaluation) {
            $ratings = json_decode($evaluation->ratings, true);
            $overallRating = round(array_sum($ratings) / count($ratings));
            $ratingDistribution[$overallRating]++;
        }

        return view('sk-eval-review', compact(
            'event', 
            'totalEvaluations', 
            'averageRatings', 
            'overallAverage',
            'ratingDistribution',
            'user', 
            'age', 
            'roleBadge'
        ));
    }
}