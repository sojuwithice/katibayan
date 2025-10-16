<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class YouthProfileController extends Controller
{
    public function index()
    {
        // Get authenticated user data for topbar
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate age from date_of_birth
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

        // Determine role badge based on actual enum values
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        // Get all users (both SK and KK) from the same barangay as the logged-in user
        $users = User::with(['region', 'province', 'city', 'barangay'])
                    ->where('account_status', 'approved')
                    ->where('barangay_id', $user->barangay_id) // Same barangay only
                    ->orderBy('last_name')
                    ->get();

        // Count certificate requests (placeholder - you'll need to implement this)
        $certificateRequestsCount = 0; // Replace with actual count logic

        return view('youth-profilepage', compact('users', 'certificateRequestsCount', 'user', 'age', 'roleBadge'));
    }
}