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

        // Calculate overall system rating from user feedback
        $ratingStats = $this->getSystemRatingStats();

        return view('admindashb', compact(
            'barangayPopulations',
            'totalPopulation',
            'pendingAccounts',
            'pendingAccountsCount',
            'recentFeedbacks',
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
     * Calculate population for each barangay from actual users with SK/KK breakdown
     */
    private function getBarangayPopulations()
    {
        // Get the specific barangays we want to track by their exact IDs
        $targetBarangays = [
            322 => 'ems_barrio',      // Em's Barrio
            323 => 'ems_barrio_south', // Em's Barrio South  
            324 => 'ems_barrio_east'   // Em's Barrio East
        ];

        Log::info('Target barangay IDs:', $targetBarangays);

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
            
            Log::info("Barangay ID $barangayId ($barangayKey) - SK: $skCount, KK: $kkCount, Total: " . $populations[$barangayKey]['total']);
        }

        // Debug: Show all users in these barangays
        $allUsers = User::whereIn('barangay_id', array_keys($targetBarangays))
            ->where('account_status', 'approved')
            ->with('barangay')
            ->get(['id', 'role', 'barangay_id', 'given_name', 'last_name']);
            
        Log::info('All approved users in target barangays:', $allUsers->toArray());

        Log::info('Final populations:', $populations);
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
     * Show the user management page with all pending accounts.
     */
    public function userManagement()
    {
        // Get the authenticated admin user
        $admin = Auth::guard('admin')->user();

        // Get both SK and KK users, sorted by creation date (newest first)
        $skUsers = User::whereIn('role', ['sk', 'kk'])
                      ->with('barangay')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('user-management', compact('skUsers', 'admin'));
    }

    /**
     * Approve user account.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        if ($user->account_status !== 'pending') {
            return back()->with('error', 'This account is not pending for approval.');
        }

        // Handle SK user approval
        if ($user->role === 'sk') {
            $registerController = new RegisterController();
            $result = $registerController->sendSKCredentials($user);

            if ($result) {
                return back()->with('success', 'SK user approved and credentials sent.');
            } else {
                return back()->with('error', 'Failed to send credentials email for SK user.');
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
                return back()->with('error', 'Youth cannot be approved yet. The SK Chair for this barangay has not been approved.');
            }

            // Proceed if SK Chair exists
            $user->account_status = 'approved';
            $user->save();

            return back()->with('success', 'KK member has been approved successfully.');
        }

        return back()->with('error', 'Invalid user role for approval.');
    }

    /**
     * Reject user account.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'rejected';
        $user->save();

        return back()->with('success', 'User account has been rejected.');
    }

    /**
     * Get admin profile data
     */
    public function getAdminProfile()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        return response()->json([
            'name' => $admin->given_name . ' ' . $admin->last_name,
            'email' => $admin->email,
            'avatar' => $admin->avatar ? asset('storage/' . $admin->avatar) : asset('images/default-avatar.png'),
            'role' => 'Administrator',
            'account_number' => $admin->account_number
        ]);
    }

    /**
     * Get population statistics for API
     */
    public function getPopulationStats()
    {
        $barangayPopulations = $this->getBarangayPopulations();
        
        $totalPopulation = 0;
        foreach ($barangayPopulations as $barangay) {
            $totalPopulation += $barangay['total'];
        }

        return response()->json([
            'barangay_populations' => $barangayPopulations,
            'total_population' => $totalPopulation
        ]);
    }
}