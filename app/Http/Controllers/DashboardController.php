<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Announcement;
use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    
    public function index(): View | RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // --- Basic User Info ---
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Member';
        Log::info("Loading dashboard for user ID: {$user->id}, Barangay ID: {$user->barangay_id}");

        // --- Notifications for Dropdown ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // --- EVALUATION PROGRESS - UPDATED TO INCLUDE BOTH EVENTS AND PROGRAMS ---
        
        // Count attended events
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count registered programs
        $registeredProgramsCount = ProgramRegistration::where('user_id', $user->id)
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total activities (events + programs)
        $totalActivities = $attendedEventsCount + $registeredProgramsCount;

        // Count evaluated events
        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('event_id')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count evaluated programs
        $evaluatedProgramsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('program_id')
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total evaluated activities
        $evaluatedActivities = $evaluatedEventsCount + $evaluatedProgramsCount;

        // Calculate activities that need evaluation
        $activitiesToEvaluate = $totalActivities - $evaluatedActivities;
        $activitiesToEvaluate = max(0, $activitiesToEvaluate);

        // Get unevaluated events for notifications
        $unevaluatedEvents = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        // Get unevaluated programs for notifications
        $unevaluatedPrograms = Program::where('barangay_id', $user->barangay_id)
            ->whereHas('programRegistrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['programRegistrations' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        // Prepare unevaluated activities for display
        $unevaluatedActivities = collect();
        
        foreach ($unevaluatedEvents as $event) {
            $unevaluatedActivities->push([
                'id' => $event->id,
                'type' => 'event',
                'title' => $event->title,
                'attendance' => $event->attendances->first(),
                'created_at' => $event->attendances->first()->created_at ?? $event->created_at
            ]);
        }
        
        foreach ($unevaluatedPrograms as $program) {
            $unevaluatedActivities->push([
                'id' => $program->id,
                'type' => 'program',
                'title' => $program->title,
                'registration' => $program->programRegistrations->first(),
                'created_at' => $program->programRegistrations->first()->created_at ?? $program->created_at
            ]);
        }

        // --- Total Notification Count for Badge ---
        $totalNotificationCount = $unreadNotificationCount + $unevaluatedActivities->count();

        // --- Upcoming Events & Holidays for Display ---
        $upcomingEvents = Event::where('is_launched', true)
            ->where('event_date', '>=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(4)
            ->get();

        $upcomingPrograms = Program::where('event_date', '>=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(2)
            ->get();

        $holidays = [
            '2025-01-01' => 'New Year\'s Day', '2025-04-09' => 'Araw ng Kagitingan', '2025-04-17' => 'Maundy Thursday',
            '2025-04-18' => 'Good Friday', '2025-05-01' => 'Labor Day', '2025-06-06' => 'Eid\'l Fitr',
            '2025-06-12' => 'Independence Day', '2025-08-25' => 'National Heroes Day', '2025-11-30' => 'Bonifacio Day',
            '2025-12-25' => 'Christmas Day', '2025-12-30' => 'Rizal Day'
        ];

        $displayItems = $this->prepareDisplayItems($upcomingEvents, $upcomingPrograms, $holidays);

        // --- Attendance Percentage (Events Only - Keep existing logic) ---
        $totalPastEvents = Event::where('is_launched', true)
            ->where('event_date', '<=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->count();

        $attendancePercentage = $totalPastEvents > 0 ? round(($attendedEventsCount / $totalPastEvents) * 100) : 0;

        // --- Announcements ---
        $announcements = Announcement::where('barangay_id', $user->barangay_id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->get();

        return view('dashboard', [
            'user' => $user,
            'age' => $age,
            'roleBadge' => $roleBadge,
            'attendedCount' => $attendedEventsCount,
            'totalEvents' => $totalPastEvents,
            'attendancePercentage' => $attendancePercentage,
            
            // Updated evaluation variables
            'totalActivities' => $totalActivities,
            'evaluatedActivities' => $evaluatedActivities,
            'activitiesToEvaluate' => $activitiesToEvaluate,
            
            // Both variables for compatibility
            'unevaluatedEvents' => $unevaluatedEvents, // Keep for template compatibility
            'unevaluatedActivities' => $unevaluatedActivities, // New combined variable
            
            'notificationCount' => $totalNotificationCount,
            'generalNotifications' => $generalNotifications,
            'displayItems' => $displayItems,
            'announcements' => $announcements,
        ]);
    }

    
    private function prepareDisplayItems($upcomingEvents, $upcomingPrograms, $holidays)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $displayHolidays = [];
        
        foreach ($holidays as $date => $name) {
            try {
                $holidayDate = Carbon::parse($date);
                $holidayMonth = $holidayDate->month;
                if ($holidayDate->year == $currentYear && ($holidayMonth == $currentMonth || $holidayMonth == $currentMonth + 1)) {
                    $displayHolidays[] = [
                        'date' => $holidayDate, 
                        'name' => $name, 
                        'month' => $holidayDate->format('M'), 
                        'day' => $holidayDate->format('d'), 
                        'is_holiday' => true
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Error parsing holiday date: $date - " . $e->getMessage());
            }
        }

        $allUpcomingItems = collect();

        // Add events
        foreach ($upcomingEvents as $event) {
            $eventDate = null;
            if ($event->event_date instanceof Carbon) {
                $eventDate = $event->event_date;
            } elseif (is_string($event->event_date)) {
                try {
                    $eventDate = Carbon::parse($event->event_date);
                } catch (\Exception $e) {
                    Log::error("Error parsing event date string for event ID {$event->id}: '{$event->event_date}' - " . $e->getMessage());
                    continue;
                }
            } else {
                Log::warning("Event date is not a Carbon instance or string for event ID {$event->id}");
                continue;
            }

            if ($eventDate) {
                $allUpcomingItems->push([
                    'type' => 'event', 
                    'date' => $eventDate, 
                    'month' => $eventDate->format('M'), 
                    'day' => $eventDate->format('d'), 
                    'title' => $event->title, 
                    'status' => 'Upcoming Event', 
                    'is_holiday' => false, 
                    'event' => $event
                ]);
            }
        }

        // Add programs
        foreach ($upcomingPrograms as $program) {
            $programDate = null;
            if ($program->event_date instanceof Carbon) {
                $programDate = $program->event_date;
            } elseif (is_string($program->event_date)) {
                try {
                    $programDate = Carbon::parse($program->event_date);
                } catch (\Exception $e) {
                    Log::error("Error parsing program date string for program ID {$program->id}: '{$program->event_date}' - " . $e->getMessage());
                    continue;
                }
            } else {
                Log::warning("Program date is not a Carbon instance or string for program ID {$program->id}");
                continue;
            }

            if ($programDate) {
                $allUpcomingItems->push([
                    'type' => 'program', 
                    'date' => $programDate, 
                    'month' => $programDate->format('M'), 
                    'day' => $programDate->format('d'), 
                    'title' => $program->title, 
                    'status' => 'Upcoming Program', 
                    'is_holiday' => false, 
                    'program' => $program
                ]);
            }
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

        return $allUpcomingItems->sortBy('date')->take(6);
    }
}