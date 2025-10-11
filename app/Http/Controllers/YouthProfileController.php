<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class YouthProfileController extends Controller
{
    public function index()
    {
        // Get all users with their location relationships
        $users = User::with(['region', 'province', 'city', 'barangay'])
                    ->where('account_status', 'approved')
                    ->orderBy('last_name')
                    ->get();

        // Count certificate requests (you'll need to adjust this based on your actual certificate request logic)
        $certificateRequestsCount = 0; // Replace with actual count logic

        return view('youth-profilepage', compact('users', 'certificateRequestsCount'));
    }
}