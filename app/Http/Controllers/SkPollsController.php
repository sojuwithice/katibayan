<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use Illuminate\Support\Facades\Auth;

class SKPollsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        // Check if user exists and has barangay_id
        if (!$user || !$user->barangay_id) {
            // Handle unauthenticated or invalid user
            $polls = collect();
            return view('sk-polls', compact('user', 'polls'));
        }
        
        // Get polls created by SK for their barangay
        $polls = Poll::with(['votes', 'user'])
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sk-polls', compact('user', 'polls'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validate user has barangay_id
        if (!$user->barangay_id) {
            return response()->json([
                'success' => false, 
                'message' => 'User is not associated with a barangay'
            ], 400);
        }
        
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

    public function getRespondents($pollId)
    {
        $poll = Poll::with(['votes.user'])->findOrFail($pollId);
        $respondents = $poll->votes->map(function($vote) {
            return [
                'name' => $vote->user->given_name . ' ' . $vote->user->last_name,
                'avatar' => $vote->user->avatar ? asset('storage/' . $vote->user->avatar) : asset('images/default-avatar.png')
            ];
        });

        return response()->json(['respondents' => $respondents]);
    }

    public function destroy($pollId)
    {
        $poll = Poll::findOrFail($pollId);
        
        // Check if user owns the poll
        if ($poll->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $poll->delete();

        return response()->json(['success' => true]);
    }
}