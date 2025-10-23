<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Log; 

class FeedbackController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'message' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        try {
            Feedback::create([
                'user_id' => auth()->id(),
                'type' => $request->type,
                'message' => $request->message,
                'rating' => $request->rating,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Feedback Submit Error: ' . $e->getMessage()); 
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred on the server.'
            ], 500); 
        }
    }
}