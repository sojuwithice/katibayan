<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CertificateRequest;
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

        // Get all approved users from the same barangay
        $users = User::with(['region', 'province', 'city', 'barangay'])
                    ->where('account_status', 'approved')
                    ->where('barangay_id', $user->barangay_id)
                    ->orderBy('last_name')
                    ->get();

        // ✅ Count certificate requests by users in the same barangay
        $certificateRequestsCount = CertificateRequest::whereIn('user_id', function ($query) use ($user) {
                $query->select('id')
                      ->from('users')
                      ->where('barangay_id', $user->barangay_id)
                      ->where('account_status', 'approved');
            })
            ->count();

        // Return to the youth-profilepage view with all data
        return view('youth-profilepage', compact('users', 'certificateRequestsCount', 'user', 'age', 'roleBadge'));
    }
}
