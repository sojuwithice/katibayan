<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EvaluationRespondentsController extends Controller
{
    /**
     * Show list of evaluation respondents for a specific event
     */
    public function showRespondents($eventId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate age from date_of_birth
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

        // Determine role badge
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        // Get event with evaluations and users
        $event = Event::findOrFail($eventId);
        
        $evaluations = Evaluation::where('event_id', $eventId)
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('list-of-eval-respondents', compact(
            'event',
            'evaluations',
            'user',
            'age',
            'roleBadge'
        ));
    }
}