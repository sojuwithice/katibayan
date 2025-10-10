<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class SKEvaluationController extends Controller
{
    /**
     * Show evaluation feedback overview
     */
    public function index()
    {
        $eventsWithEvaluations = Event::whereHas('evaluations')
            ->withCount('evaluations')
            ->with(['evaluations' => function($query) {
                $query->latest();
            }])
            ->get();

        return view('sk-evaluation-feedback', compact('eventsWithEvaluations'));
    }

    /**
     * Show detailed evaluation review for a specific event
     */
    public function showReview($eventId)
    {
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
            'ratingDistribution'
        ));
    }
}