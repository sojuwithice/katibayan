<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Calculate age and role badge
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = strtoupper($user->role) . '-Member';

        // Calculate evaluation progress
        $attendedEvents = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->count();

        $evaluatedEvents = Evaluation::where('user_id', $user->id)
            ->count();

        $eventsToEvaluate = $attendedEvents - $evaluatedEvents;

        // Get events that need evaluation (attended but not evaluated)
        $unevaluatedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->whereDoesntHave('evaluations', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('is_launched', true)
        ->orderBy('event_date', 'desc')
        ->get();

        // Calculate notification count
        $notificationCount = $unevaluatedEvents->count();

        // Get upcoming events (launched events with future dates)
        $upcomingEvents = Event::where('is_launched', true)
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(6)
            ->get();

        // Define holidays for 2025
        $holidays = [
            '2025-01-01' => 'New Year\'s Day',
            '2025-04-09' => 'Araw ng Kagitingan',
            '2025-04-17' => 'Maundy Thursday',
            '2025-04-18' => 'Good Friday',
            '2025-05-01' => 'Labor Day',
            '2025-06-06' => 'Eid\'l Fitr',
            '2025-06-12' => 'Independence Day',
            '2025-08-25' => 'National Heroes Day',
            '2025-11-30' => 'Bonifacio Day',
            '2025-12-25' => 'Christmas Day',
            '2025-12-30' => 'Rizal Day'
        ];

        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Filter holidays for current and next month
        $displayHolidays = [];
        foreach ($holidays as $date => $name) {
            $holidayDate = Carbon::parse($date);
            $holidayMonth = $holidayDate->month;
            
            // Include holidays from current month and next month
            if ($holidayDate->year == $currentYear && 
                ($holidayMonth == $currentMonth || $holidayMonth == $currentMonth + 1)) {
                $displayHolidays[] = [
                    'date' => $holidayDate,
                    'name' => $name,
                    'month' => $holidayDate->format('M'),
                    'day' => $holidayDate->format('d'),
                    'is_holiday' => true
                ];
            }
        }

        // Combine events and holidays
        $allUpcomingItems = collect();

        // Add events
        foreach ($upcomingEvents as $event) {
            $allUpcomingItems->push([
                'type' => 'event',
                'date' => $event->event_date,
                'month' => $event->event_date->format('M'),
                'day' => $event->event_date->format('d'),
                'title' => $event->title,
                'status' => 'Upcoming',
                'is_holiday' => false,
                'event' => $event
            ]);
        }

        // Add holidays
        foreach ($displayHolidays as $holiday) {
            $allUpcomingItems->push([
                'type' => 'holiday',
                'date' => $holiday['date'],
                'month' => $holiday['month'],
                'day' => $holiday['day'],
                'title' => $holiday['name'],
                'status' => 'Holiday',
                'is_holiday' => true,
                'event' => null
            ]);
        }

        // Sort by date and limit to 6 items
        $displayItems = $allUpcomingItems->sortBy('date')->take(6);

        // Calculate attendance percentage for progress
        $totalEvents = Event::where('is_launched', true)
            ->where('event_date', '<=', Carbon::today())
            ->count();

        $attendedCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->count();

        $attendancePercentage = $totalEvents > 0 ? ($attendedCount / $totalEvents * 100) : 0;

        return view('dashboard', [
            'user' => $user,
            'age' => $age,
            'roleBadge' => $roleBadge,
            'attendedCount' => $attendedCount,
            'totalEvents' => $totalEvents,
            'attendancePercentage' => $attendancePercentage,
            'attendedEvents' => $attendedEvents,
            'evaluatedEvents' => $evaluatedEvents,
            'eventsToEvaluate' => $eventsToEvaluate,
            'unevaluatedEvents' => $unevaluatedEvents,
            'notificationCount' => $notificationCount,
            'upcomingEvents' => $upcomingEvents,
            'displayItems' => $displayItems,
            'displayHolidays' => $displayHolidays,
        ]);
    }
}