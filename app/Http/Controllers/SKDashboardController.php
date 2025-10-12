<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;

class SKDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Calculate role badge and age for SK user
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'SK-Member';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('sk-dashboard', compact('user', 'roleBadge', 'age'));
    }
}