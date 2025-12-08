<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Poll;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Announcement;
use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PollsController extends Controller
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

        // Get active polls for user's barangay
        $polls = Poll::with(['votes', 'user'])
            ->where('barangay_id', $user->barangay_id)
            ->where('is_active', true)
            ->where('end_date', '>=', now()->format('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get();

        // ALWAYS SHOW ALL COMMITTEES (hardcoded to ensure all options appear)
        $committees = [
            'Active Citizenship',
            'Economic Empowerment', 
            'Education',
            'Health',
            'Sports'
        ];

        // --- NOTIFICATIONS SECTION (SAME AS DASHBOARD) ---
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

        return view('pollspage', compact(
            'user', 
            'roleBadge', 
            'age', 
            'polls', 
            'committees',
            'generalNotifications',
            'unevaluatedActivities',
            'totalNotificationCount'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'end_date' => 'required|date|after:today',
            'committee' => 'nullable|string|max:255'
        ]);

        $poll = Poll::create([
            'user_id' => $user->id,
            'barangay_id' => $user->barangay_id,
            'question' => $request->question,
            'options' => $request->options,
            'end_date' => $request->end_date,
            'committee' => $request->committee,
            'is_active' => true
        ]);

        return response()->json(['success' => true, 'poll' => $poll]);
    }

    public function vote(Request $request, $pollId)
    {
        $user = Auth::user();
        
        $request->validate([
            'option_index' => 'required|integer'
        ]);

        $poll = Poll::findOrFail($pollId);

        // Check if user has already voted
        if ($poll->userHasVoted($user->id)) {
            return response()->json(['error' => 'You have already voted on this poll'], 422);
        }

        // Check if poll is still active
        if (!$poll->is_active || $poll->end_date < now()->format('Y-m-d')) {
            return response()->json(['error' => 'This poll is no longer active'], 422);
        }

        // Create vote
        $vote = $poll->votes()->create([
            'user_id' => $user->id,
            'option_index' => $request->option_index
        ]);

        return response()->json(['success' => true, 'vote' => $vote]);
    }

    public function resetVote($pollId)
    {
        $user = Auth::user();
        
        $poll = Poll::findOrFail($pollId);

        // Check if user has voted
        if (!$poll->userHasVoted($user->id)) {
            return response()->json(['error' => 'You have not voted on this poll yet'], 422);
        }

        // Check if poll is still active
        if (!$poll->is_active || $poll->end_date < now()->format('Y-m-d')) {
            return response()->json(['error' => 'This poll is no longer active'], 422);
        }

        // Delete the user's vote
        $poll->votes()->where('user_id', $user->id)->delete();

        return response()->json(['success' => true]);
    }

    public function getPollResults($pollId)
    {
        $poll = Poll::with(['votes.user'])->findOrFail($pollId);
        $voteCounts = $poll->getVoteCounts();
        $totalVotes = $poll->getTotalVotes();
        $userVote = $poll->getUserVote(Auth::id());

        return response()->json([
            'poll' => $poll,
            'vote_counts' => $voteCounts,
            'total_votes' => $totalVotes,
            'user_vote' => $userVote
        ]);
    }
}