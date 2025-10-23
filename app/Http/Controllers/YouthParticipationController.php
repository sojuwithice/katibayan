<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Attendance;
use Carbon\Carbon;

class YouthParticipationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate age from date_of_birth (correct field name)
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

        // Determine role badge based on actual enum values
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        // Get launched events with attendance count for the user's barangay
        $events = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->withCount(['attendances'])
            ->orderBy('event_date', 'desc')
            ->get();

        // Get unique categories for dropdown
        $categories = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->distinct()
            ->pluck('category')
            ->map(function($category) {
                return ucfirst(str_replace('_', ' ', $category));
            });

        // Get top active youth based on attendance count
        $topYouth = Attendance::whereHas('event', function($query) use ($user) {
                $query->where('barangay_id', $user->barangay_id)
                      ->where('is_launched', true);
            })
            ->with('user')
            ->selectRaw('user_id, COUNT(*) as attendance_count')
            ->groupBy('user_id')
            ->orderBy('attendance_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($attendance) {
                $user = $attendance->user;
                if (!$user) return null;
                
                // Build full name properly using the correct field names
                $fullnameParts = array_filter([
                    $user->given_name ?? '',
                    $user->middle_name ?? '',
                    $user->last_name ?? '',
                    $user->suffix ?? ''
                ]);
                $fullname = implode(' ', $fullnameParts);

                return [
                    'name' => $fullname ?: 'Unknown User',
                    'attendance_count' => $attendance->attendance_count,
                    'avatar' => $user->avatar
                ];
            })->filter(); // Remove null entries

        return view('youth-participation', [
            'user' => $user,
            'age' => $age,
            'roleBadge' => $roleBadge,
            'events' => $events,
            'categories' => $categories,
            'topYouth' => $topYouth
        ]);
    }
}