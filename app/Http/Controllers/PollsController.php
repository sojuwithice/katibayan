<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Poll;
use App\Models\Barangay;
use Illuminate\Http\Request;

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

        return view('pollspage', compact('user', 'roleBadge', 'age', 'polls', 'committees'));
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