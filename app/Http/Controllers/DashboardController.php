<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Attendance progress
        $attendedCount = Attendance::where('user_id', $user->id)->count();
        $totalEvents = Event::count();
        $attendancePercentage = $totalEvents > 0 ? ($attendedCount / $totalEvents) * 100 : 0;

        return view('dashboard', [
            'user' => $user,
            'attendedCount' => $attendedCount,
            'totalEvents' => $totalEvents,
            'attendancePercentage' => $attendancePercentage,
        ]);
    }
}
