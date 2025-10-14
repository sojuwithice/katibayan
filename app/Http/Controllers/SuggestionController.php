<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;

class SuggestionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $age = $user && $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';

        return view('suggestionbox', compact('user', 'age', 'roleBadge'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'committee' => 'required|in:active,economic,education,health,sports',
            'suggestions' => 'required|string|min:10|max:1000'
        ]);

        try {
            $suggestion = Suggestion::create([
                'user_id' => Auth::id(),
                'committee' => $validated['committee'],
                'suggestions' => $validated['suggestions'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suggestion submitted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit suggestion. Please try again.'
            ], 500);
        }
    }

    public function youthSuggestion()
    {
        $user = Auth::user();
        $age = $user && $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user && $user->role ? strtoupper($user->role) . '-Member' : 'GUEST';

        return view('youth-suggestion', compact('user', 'age', 'roleBadge'));
    }

    public function getSKSuggestions()
    {
        try {
            $user = Auth::user();
            
            // Get suggestions from the same barangay
            $suggestions = Suggestion::with('user')
                ->whereHas('user', function($query) use ($user) {
                    $query->where('barangay_id', $user->barangay_id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch suggestions'
            ], 500);
        }
    }
}