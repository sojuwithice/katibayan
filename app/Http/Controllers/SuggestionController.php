<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to submit a suggestion.'
                ], 401);
            }

            $user = Auth::user();

            $suggestion = Suggestion::create([
                'user_id' => $user->id,
                'committee' => $validated['committee'],
                'suggestions' => $validated['suggestions'],
                'barangay_id' => $user->barangay_id // ADD THIS LINE
            ]);

            Log::info('Suggestion submitted successfully', [
                'user_id' => $user->id,
                'suggestion_id' => $suggestion->id,
                'committee' => $validated['committee'],
                'barangay_id' => $user->barangay_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suggestion submitted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to submit suggestion', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit suggestion: ' . $e->getMessage()
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
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Get suggestions from the same barangay
            $suggestions = Suggestion::with('user')
                ->where('barangay_id', $user->barangay_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch suggestions', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch suggestions: ' . $e->getMessage()
            ], 500);
        }
    }
}