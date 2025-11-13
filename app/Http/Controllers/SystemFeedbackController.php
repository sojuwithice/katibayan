<?php

namespace App\Http\Controllers;

use App\Models\SystemFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SystemFeedbackController extends Controller
{
    /**
     * Store a newly created feedback in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:suggestion,bug,appreciation,others',
            'message' => 'required|string|min:10|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ], [
            'message.required' => 'Please enter your feedback message.',
            'message.min' => 'Feedback message must be at least 10 characters.',
            'message.max' => 'Feedback message must not exceed 1000 characters.',
            'type.required' => 'Please select a feedback type.',
            'rating.min' => 'Rating must be between 1 and 5 stars.',
            'rating.max' => 'Rating must be between 1 and 5 stars.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = SystemFeedback::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'message' => $request->message,
                'rating' => $request->rating,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback! We appreciate your input.',
                'data' => $feedback
            ], 201);

        } catch (\Exception $e) {
            Log::error('System feedback submission error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback. Please try again.'
            ], 500);
        }
    }

    /**
     * Display a listing of the feedback for admin.
     */
    public function index()
    {
        $feedbacks = SystemFeedback::with(['user', 'user.barangay'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalFeedbacks = SystemFeedback::count();
        $pendingFeedbacks = SystemFeedback::where('status', 'pending')->count();

        return view('users-feedback', compact('feedbacks', 'totalFeedbacks', 'pendingFeedbacks'));
    }

    /**
     * Update the feedback status.
     */
    public function updateStatus(Request $request, SystemFeedback $systemFeedback)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved'
        ]);

        $systemFeedback->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback status updated successfully.'
        ]);
    }

    /**
     * Get feedback statistics for dashboard.
     */
    public function getStats()
    {
        $total = SystemFeedback::count();
        $pending = SystemFeedback::pending()->count();
        $byType = SystemFeedback::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'by_type' => $byType
        ]);
    }
}