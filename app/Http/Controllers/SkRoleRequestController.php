<?php

namespace App\Http\Controllers;

use App\Models\SkRoleRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SkRoleRequestController extends Controller
{
    /**
     * PART 1: User hihingi ng access
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Prevent multiple pending requests
        $existingRequest = SkRoleRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json(['success' => false, 'message' => 'You already have a pending request.'], 400);
        }

        // Create the request
        $newRequest = SkRoleRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        // === FIX: Send notification to users with role 'sk' (hindi sk_chairperson) ===
        $skUsers = User::where('role', 'sk') // <--- PINALITAN DITO
            ->where('barangay_id', $user->barangay_id)
            ->get();

        foreach ($skUsers as $skUser) {
            Notification::create([
                'user_id' => $skUser->id,
                'title' => 'New SK Role Request',
                'message' => $user->given_name . ' ' . $user->last_name . ' is requesting access to SK role.',
                'type' => 'sk_role_request',
                'recipient_role' => 'sk', // Matches the dashboard filter
                'is_read' => false,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Request submitted successfully!']);
    }

    /**
     * PART 2: SK Chair/Admin kukunin 'yung listahan
     */
    public function index()
    {
        $requests = SkRoleRequest::where('status', 'pending')
            ->with('user:id,given_name,last_name') 
            ->latest()     
            ->get();

        return response()->json($requests);
    }

    /**
     * PART 3: SK Chair approve request
     */
    public function approve($id)
    {
        try {
            $roleRequest = SkRoleRequest::findOrFail($id);
            $roleRequest->status = 'approved';
            $roleRequest->save(); 

            $user = $roleRequest->user;

            if (!$user) {
                Log::error("Approve failed: User is null for sk_role_request ID {$id}");
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            // Notify requesting user
            Notification::create([
                'user_id' => $user->id,
                'title' => 'SK Request Accepted',
                'message' => 'Your request has successfully accepted. Click this to set your role.',
                'type' => 'sk_request_approved',
                'recipient_role' => 'kk', 
                'is_read' => false,
            ]);

            return response()->json(['success' => true, 'message' => 'Request approved.']);
            
        } catch (\Exception $e) {
            Log::error('Approve error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred during approval.']);
        }
    }

    /**
     * PART 4: SK Chair reject request
     */
    public function reject($id)
    {
        $roleRequest = SkRoleRequest::findOrFail($id);
        $roleRequest->status = 'rejected';
        $roleRequest->save();

        $user = $roleRequest->user;

        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'SK Request Rejected',
                'message' => 'Your request to access the SK role has been rejected.',
                'type' => 'sk_request_rejected',
                'recipient_role' => 'kk',
                'is_read' => false,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Request rejected.']);
    }

    /**
     * PART 5: User sets specific role after approval
     */
    public function setRole(Request $request)
    {
        // 1. Validate
        $request->validate([
            'role' => 'required|string|in:Kagawad,Secretary,Treasurer',
        ]);

        $user = Auth::user();

        // 2. SAVE SA BAGONG COLUMN (sk_role)
        // Hindi nito gagalawin ang main 'role' column mo.
        $user->sk_role = $request->role; 
        
        $user->save();

        // 3. Clear existing request
        SkRoleRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->delete();

        return response()->json([
            'success' => true, 
            'message' => 'SK Role updated successfully!'
        ]);
    }

    // Idagdag ito sa iyong SkRoleRequestController
    public function updateCommittees(Request $request)
    {
        $request->validate([
            'committees' => 'required|array', // Dapat array ang ipapasa galing JS
        ]);

        $user = Auth::user();
        
        // I-save bilang JSON string (e.g., ["health", "education"])
        $user->committees = json_encode($request->committees);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Committees updated successfully!'
        ]);
    }
}