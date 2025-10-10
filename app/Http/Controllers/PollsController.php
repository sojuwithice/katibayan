<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PollsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('loginpage');
        }

        // Calculate age
        $age = 'N/A';
        if ($user->date_of_birth) {
            try {
                $age = Carbon::parse($user->date_of_birth)->age;
            } catch (\Exception $e) {
                $age = 'N/A';
            }
        }

        $roleBadge = strtoupper($user->role) . '-Member';

        // ✅ include $age in compact()
        return view('pollspage', compact('user', 'roleBadge', 'age'));
    }
}
