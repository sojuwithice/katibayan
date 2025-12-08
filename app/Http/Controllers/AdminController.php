<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barangay;
use App\Models\SystemFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCredentialsMail;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert; // ADD THIS LINE

class AdminController extends Controller
{
    /**
     * Show the main admin dashboard page with statistics.
     */
    public function dashboard()
    {
        // Get the authenticated admin user
        $admin = Auth::guard('admin')->user();

        // Calculate population data from actual users with SK/KK breakdown
        $barangayPopulations = $this->getBarangayPopulations();
        
        // Debug: Check what data we're getting
        Log::info('Barangay Populations:', $barangayPopulations);
        
        // Calculate total population correctly
        $totalPopulation = 0;
        foreach ($barangayPopulations as $barangay) {
            $totalPopulation += $barangay['total'];
        }
        
        // Get pending accounts for the manage section
        $pendingAccounts = User::where('account_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $pendingAccountsCount = User::where('account_status', 'pending')->count();

        // Get recent feedback comments with user data
        $recentFeedbacks = SystemFeedback::with(['user' => function($query) {
                $query->select('id', 'account_number', 'given_name', 'last_name', 'avatar');
            }])
            ->whereNotNull('rating')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get recent pending feedbacks for notifications (last 5 pending feedbacks)
        $recentPendingFeedbacks = SystemFeedback::with(['user' => function($query) {
                $query->select('id', 'account_number', 'given_name', 'last_name', 'avatar', 'barangay_id')
                      ->with('barangay:id,name');
            }])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pendingFeedbacksCount = SystemFeedback::where('status', 'pending')->count();

        // Calculate overall system rating from user feedback
        $ratingStats = $this->getSystemRatingStats();

        return view('admindashb', compact(
            'barangayPopulations',
            'totalPopulation',
            'pendingAccounts',
            'pendingAccountsCount',
            'recentFeedbacks',
            'recentPendingFeedbacks',
            'pendingFeedbacksCount',
            'admin',
            'ratingStats'
        ));
    }

    /**
     * Show the admin analytics page with detailed statistics.
     */
    public function analytics()
    {
        // Get the authenticated admin user
        $admin = Auth::guard('admin')->user();

        // Calculate population data from actual users with SK/KK breakdown
        $barangayPopulations = $this->getBarangayPopulations();
        
        // Calculate total population correctly
        $totalPopulation = 0;
        foreach ($barangayPopulations as $barangay) {
            $totalPopulation += $barangay['total'];
        }

        // Calculate SK and KK counts for distribution chart
        $skCount = User::where('role', 'sk')
            ->where('account_status', 'approved')
            ->count();
            
        $kkCount = User::where('role', 'kk')
            ->where('account_status', 'approved')
            ->count();

        // Get recent users for activity overview
        $recentUsers = User::with('barangay')
            ->where('account_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get system rating statistics
        $ratingStats = $this->getSystemRatingStats();

        return view('admin-analytics', compact(
            'barangayPopulations',
            'totalPopulation',
            'skCount',
            'kkCount',
            'recentUsers',
            'ratingStats',
            'admin'
        ));
    }

    /**
     * Show the user management page with tabs for SK and KK
     */
    public function userManagement()
    {
        // Get the authenticated admin user
        $admin = Auth::guard('admin')->user();

        // DEBUG: Check database counts directly
        $totalUsers = User::count();
        $skUsersCount = User::where('role', 'sk')->count();
        $kkUsersCount = User::where('role', 'kk')->count();
        $pendingSkCount = User::where('role', 'sk')->where('account_status', 'pending')->count();
        
        Log::info('Database Counts:', [
            'total_users' => $totalUsers,
            'sk_users' => $skUsersCount,
            'kk_users' => $kkUsersCount,
            'pending_sk' => $pendingSkCount
        ]);

        // Get both SK and KK users with related data
        $skUsers = User::with(['barangay', 'city', 'province', 'region'])
                      ->where(function($query) {
                          $query->where('role', 'sk')
                                ->orWhere('role', 'kk');
                      })
                      ->orderBy('created_at', 'desc')
                      ->get();

        // Count statistics for tabs
        $skCount = $skUsers->where('role', 'sk')->count();
        $kkCount = $skUsers->where('role', 'kk')->count();
        $pendingSkCount = $skUsers->where('role', 'sk')->where('account_status', 'pending')->count();

        Log::info('User Management Statistics:', [
            'collection_sk_count' => $skCount,
            'collection_kk_count' => $kkCount,
            'collection_pending_sk' => $pendingSkCount
        ]);

        return view('user-management', compact(
            'skUsers', 
            'admin', 
            'skCount', 
            'kkCount',
            'pendingSkCount'
        ));
    }

    /**
     * Calculate population for each barangay from actual users with SWEETALERT EXAMPLES
     */
    private function getBarangayPopulations()
    {
        // Get the specific barangays we want to track by their exact IDs
        $targetBarangays = [
            322 => 'ems_barrio',      // Em's Barrio
            323 => 'ems_barrio_south', // Em's Barrio South  
            324 => 'ems_barrio_east'   // Em's Barrio East
        ];

        // Initialize population counts with SK/KK breakdown
        $populations = [
            'ems_barrio' => ['sk' => 0, 'kk' => 0, 'total' => 0],
            'ems_barrio_south' => ['sk' => 0, 'kk' => 0, 'total' => 0],
            'ems_barrio_east' => ['sk' => 0, 'kk' => 0, 'total' => 0]
        ];

        // Count users for each barangay directly by barangay_id
        foreach ($targetBarangays as $barangayId => $barangayKey) {
            // Get SK users for this barangay
            $skCount = User::where('barangay_id', $barangayId)
                ->where('role', 'sk')
                ->where('account_status', 'approved')
                ->count();
                
            // Get KK users for this barangay  
            $kkCount = User::where('barangay_id', $barangayId)
                ->where('role', 'kk')
                ->where('account_status', 'approved')
                ->count();
                
            // Update populations
            $populations[$barangayKey]['sk'] = $skCount;
            $populations[$barangayKey]['kk'] = $kkCount;
            $populations[$barangayKey]['total'] = $skCount + $kkCount;
        }

        return $populations;
    }

    /**
     * Calculate system rating statistics from user feedback
     */
    private function getSystemRatingStats()
    {
        // Get all feedbacks with ratings
        $feedbacksWithRatings = SystemFeedback::whereNotNull('rating')->get();
        
        $totalFeedbacks = $feedbacksWithRatings->count();
        
        if ($totalFeedbacks === 0) {
            return [
                'average_rating' => 0,
                'total_ratings' => 0,
                'rating_percentage' => 0,
                'rating_distribution' => [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0
                ]
            ];
        }

        // Calculate average rating
        $totalRating = $feedbacksWithRatings->sum('rating');
        $averageRating = $totalRating / $totalFeedbacks;
        
        // Calculate percentage (out of 5 stars)
        $ratingPercentage = ($averageRating / 5) * 100;

        // Calculate rating distribution
        $ratingDistribution = [
            1 => $feedbacksWithRatings->where('rating', 1)->count(),
            2 => $feedbacksWithRatings->where('rating', 2)->count(),
            3 => $feedbacksWithRatings->where('rating', 3)->count(),
            4 => $feedbacksWithRatings->where('rating', 4)->count(),
            5 => $feedbacksWithRatings->where('rating', 5)->count()
        ];

        return [
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $totalFeedbacks,
            'rating_percentage' => round($ratingPercentage),
            'rating_distribution' => $ratingDistribution
        ];
    }

    /**
     * Get complete user details for modal view
     */
    public function getUserDetails($id)
    {
        try {
            $user = User::with(['barangay', 'city', 'province', 'region'])
                        ->findOrFail($id);

            // Build complete address
            $addressParts = [];
            if (!empty($user->purok_zone)) {
                $addressParts[] = $user->purok_zone;
            }
            if (!empty($user->barangay->name ?? null)) {
                $addressParts[] = $user->barangay->name;
            }
            if (!empty($user->city->name ?? null)) {
                $addressParts[] = $user->city->name;
            }
            if (!empty($user->province->name ?? null)) {
                $addressParts[] = $user->province->name;
            }
            if (!empty($user->region->name ?? null)) {
                $addressParts[] = $user->region->name;
            }
            if (!empty($user->zip_code)) {
                $addressParts[] = $user->zip_code;
            }

            $age = Carbon::parse($user->date_of_birth)->age;

            Log::info('User details loaded', [
                'user_id' => $id,
                'role' => $user->role,
                'account_status' => $user->account_status
            ]);

            return response()->json([
                'success' => true,
                'full_name' => $user->given_name . ' ' . $user->last_name . ($user->suffix ? ' ' . $user->suffix : ''),
                'account_number' => $user->account_number,
                'role' => $user->role,
                'account_status' => $user->account_status,
                'is_locked' => $user->is_locked ?? false,
                'email' => $user->email,
                'contact_no' => $user->contact_no,
                'date_of_birth' => $user->date_of_birth ? Carbon::parse($user->date_of_birth)->format('F d, Y') : 'N/A',
                'age' => $age,
                'sex' => ucfirst($user->sex),
                'civil_status' => $user->civil_status,
                'education' => $user->education,
                'work_status' => $user->work_status,
                'youth_classification' => $user->youth_classification,
                'sk_voter' => $user->sk_voter,
                'address' => implode(', ', $addressParts) ?: 'No address provided',
                'barangay' => $user->barangay->name ?? 'N/A',
                'city' => $user->city->name ?? 'N/A',
                'province' => $user->province->name ?? 'N/A',
                'region' => $user->region->name ?? 'N/A',
                'purok_zone' => $user->purok_zone ?? 'N/A',
                'zip_code' => $user->zip_code ?? 'N/A',
                'sk_role' => $user->sk_role,
                'committees' => $user->committees,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png'),
                'created_at' => $user->created_at->format('F d, Y h:i A'),
                'updated_at' => $user->updated_at->format('F d, Y h:i A'),
                'locked_at' => $user->locked_at ? Carbon::parse($user->locked_at)->format('F d, Y h:i A') : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load user details'
            ], 500);
        }
    }
/**
 * Check if SK official can be deleted
 */
public function checkSKDelete(Request $request, $id)
{
    try {
        $user = User::findOrFail($id);
        $barangayId = $request->input('barangay_id');
        
        if ($user->role !== 'sk') {
            return response()->json([
                'can_delete' => true,
                'message' => 'User is not an SK official'
            ]);
        }
        
        // Check if there are KK members depending on this SK official
        $kkCount = User::where('barangay_id', $barangayId)
            ->where('role', 'kk')
            ->where('account_status', 'approved')
            ->count();
        
        // Check if there are other SK officials in the same barangay
        $otherSkCount = User::where('barangay_id', $barangayId)
            ->where('role', 'sk')
            ->where('account_status', 'approved')
            ->where('id', '!=', $user->id)
            ->count();
        
        $canDelete = true;
        $message = '';
        
        if ($kkCount > 0 && $otherSkCount === 0) {
            $canDelete = false;
            $message = 'Cannot delete SK official. There are KK members in this barangay and no other SK official to take over.';
        } else if ($kkCount > 0 && $otherSkCount > 0) {
            $message = 'SK official can be deleted. There is another SK official in this barangay to take over the KK members.';
        } else if ($kkCount === 0) {
            $message = 'SK official can be deleted. There are no KK members in this barangay.';
        }
        
        return response()->json([
            'can_delete' => $canDelete,
            'message' => $message,
            'kk_count' => $kkCount,
            'other_sk_count' => $otherSkCount
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error checking SK deletion: ' . $e->getMessage());
        return response()->json([
            'can_delete' => false,
            'message' => 'Error checking deletion conditions'
        ], 500);
    }
}
    public function deleteUser($id)
{
    try {
        $user = User::findOrFail($id);
        
        // Check if user can be deleted
        if ($user->role === 'sk' && $user->account_status === 'approved') {
            // Check if there are KK members depending on this SK official
            $kkCount = User::where('barangay_id', $user->barangay_id)
                ->where('role', 'kk')
                ->where('account_status', 'approved')
                ->count();
            
            // Check if there are other SK officials in the same barangay
            $otherSkCount = User::where('barangay_id', $user->barangay_id)
                ->where('role', 'sk')
                ->where('account_status', 'approved')
                ->where('id', '!=', $user->id)
                ->count();
            
            if ($kkCount > 0 && $otherSkCount === 0) {
                // SweetAlert error response - cannot delete because there are KK members and no other SK
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete SK official. There are KK members in this barangay and no other SK official to take over.'
                ], 400);
            }
            
            // If there are KK members but there's another SK official, allow deletion
            if ($kkCount > 0 && $otherSkCount > 0) {
                // This is allowed - there's another SK official to take over the KK members
                Log::info('SK deletion allowed - another SK official exists in barangay', [
                    'user_id' => $id,
                    'barangay_id' => $user->barangay_id,
                    'other_sk_count' => $otherSkCount,
                    'kk_count' => $kkCount
                ]);
            }
        }
        
        // Soft delete the user
        $user->delete();
        
        Log::info('User deleted', ['user_id' => $id, 'deleted_by' => Auth::guard('admin')->user()->id]);
        
        // SweetAlert success response
        return response()->json([
            'success' => true,
            'message' => 'User account deleted successfully'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error deleting user: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete user account'
        ], 500);
    }
}

    /**
     * Lock or unlock user account - WITH SWEETALERT EXAMPLE
     */
    public function toggleLock(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $action = $request->input('action');
            
            if (!in_array($action, ['lock', 'unlock'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ], 400);
            }
            
            // Update lock status
            $user->is_locked = ($action === 'lock');
            $user->locked_at = ($action === 'lock') ? now() : null;
            $user->locked_by = ($action === 'lock') ? Auth::guard('admin')->user()->id : null;
            $user->save();
            
            $actionText = $action === 'lock' ? 'locked' : 'unlocked';
            
            // Log the action
            Log::info('User account ' . $actionText, [
                'user_id' => $id,
                'account_number' => $user->account_number,
                'action' => $action,
                'action_by' => Auth::guard('admin')->user()->id,
                'timestamp' => now()
            ]);
            
            // SweetAlert success response
            return response()->json([
                'success' => true,
                'message' => 'User account ' . $actionText . ' successfully',
                'user' => [
                    'id' => $user->id,
                    'is_locked' => $user->is_locked,
                    'status_badge' => $this->getStatusBadge($user->account_status, $user->is_locked)
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling user lock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update account status'
            ], 500);
        }
    }

    /**
     * Helper method to get status badge HTML
     */
    private function getStatusBadge($accountStatus, $isLocked)
    {
        if ($isLocked) {
            return '<span class="status-badge status-locked">Locked</span>';
        }
        
        switch($accountStatus) {
            case 'pending': return '<span class="status-badge status-pending">Pending</span>';
            case 'approved': return '<span class="status-badge status-approved">Approved</span>';
            case 'rejected': return '<span class="status-badge status-rejected">Rejected</span>';
            default: return '<span class="status-badge status-active">Active</span>';
        }
    }

    /**
     * Approve user account - WITH SWEETALERT SUPPORT
     */
    public function approve($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->account_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This account is not pending for approval.'
                ], 400);
            }

            // Handle SK user approval
            if ($user->role === 'sk') {
                $registerController = new RegisterController();
                $result = $registerController->sendSKCredentials($user);

                if ($result) {
                    // SweetAlert success
                    return response()->json([
                        'success' => true,
                        'message' => 'SK user approved and credentials sent.'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to send credentials email for SK user.'
                    ], 500);
                }
            }

            // Handle KK (Youth) user approval
            if ($user->role === 'kk') {
                // Check if there is at least one approved SK Chair in the same barangay
                $hasApprovedChair = User::where('role', 'sk')
                    ->where('barangay_id', $user->barangay_id)
                    ->where('account_status', 'approved')
                    ->exists();

                if (!$hasApprovedChair) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Youth cannot be approved yet. The SK Chair for this barangay has not been approved.'
                    ], 400);
                }

                // Proceed if SK Chair exists
                $user->account_status = 'approved';
                $user->save();

                // SweetAlert success
                return response()->json([
                    'success' => true,
                    'message' => 'KK member has been approved successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid user role for approval.'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error approving user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve user account'
            ], 500);
        }
    }

    /**
     * Reject user account - WITH SWEETALERT SUPPORT
     */
    public function reject($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->account_status = 'rejected';
            $user->save();

            // SweetAlert success
            return response()->json([
                'success' => true,
                'message' => 'User account has been rejected.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject user account'
            ], 500);
        }
    }

    /**
     * Get notifications for admin
     */
    public function getNotifications()
    {
        try {
            // Get recent pending feedbacks
            $recentPendingFeedbacks = SystemFeedback::with(['user' => function($query) {
                    $query->select('id', 'account_number', 'given_name', 'last_name', 'avatar', 'barangay_id')
                          ->with('barangay:id,name');
                }])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $notifications = $recentPendingFeedbacks->map(function($feedback) {
                return [
                    'id' => $feedback->id,
                    'title' => 'New Feedback',
                    'message' => 'Feedback from ' . $feedback->user->given_name . ' ' . $feedback->user->last_name,
                    'time_ago' => $feedback->created_at->diffForHumans(),
                    'type' => 'feedback'
                ];
            });
            
            // Get pending accounts count
            $pendingAccountsCount = User::where('account_status', 'pending')->count();
            
            // Add pending accounts notification if any
            if ($pendingAccountsCount > 0) {
                $notifications->prepend([
                    'id' => 'pending_accounts',
                    'title' => 'Pending Accounts',
                    'message' => 'You have ' . $pendingAccountsCount . ' pending account' . ($pendingAccountsCount > 1 ? 's' : '') . ' to review',
                    'time_ago' => 'Just now',
                    'type' => 'accounts'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'count' => $notifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notifications' => [],
                'count' => 0
            ]);
        }
    }
    
    /**
     * NEW: Test method to show SweetAlert
     */
    public function testSweetAlert()
    {
        // Different types of SweetAlerts
        Alert::success('Success Title', 'Success Message');
        Alert::info('Info Title', 'Info Message');
        Alert::warning('Warning Title', 'Warning Message');
        Alert::error('Error Title', 'Error Message');
        Alert::question('Question Title', 'Question Message');
        
        // Toast notification
        Alert::toast('User approved successfully!', 'success');
        
        return back();
    }
}