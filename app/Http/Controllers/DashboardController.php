<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Calculate age and role badge
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = strtoupper($user->role) . '-Member';

        Log::info("Loading dashboard for user ID: {$user->id}, Barangay ID: {$user->barangay_id}");

        // Calculate evaluation progress
        $attendedEvents = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->count();

        $evaluatedEvents = Evaluation::where('user_id', $user->id)
            ->count();

        $eventsToEvaluate = $attendedEvents - $evaluatedEvents;

        // Get events that need evaluation
        $unevaluatedEvents = Event::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereNotNull('attended_at');
        })
        ->whereDoesntHave('evaluations', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('is_launched', true)
        ->where('barangay_id', $user->barangay_id)
        ->orderBy('event_date', 'desc')
        ->get();

        // CREATE NOTIFICATIONS FOR UNEVALUATED EVENTS
        foreach ($unevaluatedEvents as $event) {
            // Get the attendance date for this event
            $attendance = $event->attendances()->where('user_id', $user->id)->first();
            $attendedDate = $attendance ? $attendance->attended_at->format('M j, Y') : 'recently';
            
            // Check if notification already exists for this event
            $existingNotification = Notification::where('user_id', $user->id)
                ->where('type', 'evaluation_reminder')
                ->where('message', 'LIKE', "%evaluate '{$event->title}'%")
                ->first();

            if (!$existingNotification) {
                // Create notification with evaluation_id as NULL
                Notification::create([
                    'user_id' => $user->id,
                    'evaluation_id' => null, // This is now allowed to be NULL
                    'type' => 'evaluation_reminder',
                    'message' => "Please evaluate '{$event->title}' - attended on {$attendedDate}",
                    'is_read' => false,
                ]);
                
                Log::info("Created evaluation notification for user {$user->id}, event {$event->id}");
            }
        }

        // Calculate notification count from database
        $notificationCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        Log::info("User {$user->id} has {$notificationCount} unread notifications");

        // Get upcoming events
        $upcomingEvents = Event::where('is_launched', true)
            ->where('event_date', '>=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(6)
            ->get();

        Log::info("Upcoming events found for barangay {$user->barangay_id}: " . $upcomingEvents->count());

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

        // Calculate attendance percentage
        $totalEvents = Event::where('is_launched', true)
            ->where('event_date', '<=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->count();

        $attendedCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', function($query) use ($user) {
                $query->where('barangay_id', $user->barangay_id);
            })
            ->count();

        $attendancePercentage = $totalEvents > 0 ? ($attendedCount / $totalEvents * 100) : 0;

        Log::info("Dashboard stats - Total events: {$totalEvents}, Attended: {$attendedCount}, Percentage: {$attendancePercentage}%");

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