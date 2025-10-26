<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Announcement;         // <-- Importante
use App\Models\CertificateSchedule; 
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Added for redirect
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index(): View | RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            // Handle case where user is not authenticated, maybe redirect to login
            return redirect()->route('login');
        }


        // --- Basic User Info ---
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Member'; // Added default
        Log::info("Loading dashboard for user ID: {$user->id}, Barangay ID: {$user->barangay_id}");

        // --- Notifications for Dropdown ---
        // Kukunin ang lahat ng notifs para sa user na 'to
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
         // Use the loaded collection to count unread for efficiency
        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // --- Evaluation Progress & Count ---
        // Pinalitan ko name para mas malinaw
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            // Add barangay filter here too for accuracy if needed
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();
        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
             // Add barangay filter here too
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();
        // Calculate based on attended events *within the user's barangay* that require evaluation
         $eventsRequiringEvaluation = Event::where('is_launched', true)
            ->where('barangay_id', $user->barangay_id)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })->count();
        $eventsToEvaluateCount = $eventsRequiringEvaluation - $evaluatedEventsCount;
        // Ensure it's not negative if counts mismatch somehow
        $eventsToEvaluateCount = max(0, $eventsToEvaluateCount);

        // Get unevaluated event details for the notification list
        $unevaluatedEvents = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('event_date', 'desc')
            ->get();


        // --- Total Notification Count for Badge ---
        $totalNotificationCount = $unreadNotificationCount + $unevaluatedEvents->count();

        // --- Upcoming Events & Holidays for Display ---
        $upcomingEvents = Event::where('is_launched', true)
            ->where('event_date', '>=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(6) // Limit events *before* merging with holidays
            ->get();
        $holidays = [
             '2025-01-01' => 'New Year\'s Day', '2025-04-09' => 'Araw ng Kagitingan', '2025-04-17' => 'Maundy Thursday',
             '2025-04-18' => 'Good Friday', '2025-05-01' => 'Labor Day', '2025-06-06' => 'Eid\'l Fitr',
             '2025-06-12' => 'Independence Day', '2025-08-25' => 'National Heroes Day', '2025-11-30' => 'Bonifacio Day',
             '2025-12-25' => 'Christmas Day', '2025-12-30' => 'Rizal Day'
        ];
        $displayItems = $this->prepareDisplayItems($upcomingEvents, $holidays); // <- Helper function

        // --- Attendance Percentage ---
        $totalPastEvents = Event::where('is_launched', true)
            ->where('event_date', '<=', Carbon::today()) // Past or today
            ->where('barangay_id', $user->barangay_id)
            ->count();
        // Use the count already calculated
        // $attendedCount = Attendance::where(...) ->count();
        $attendancePercentage = $totalPastEvents > 0 ? round(($attendedEventsCount / $totalPastEvents) * 100) : 0; // Round off

        //
        $announcements = Announcement::where('barangay_id', $user->barangay_id)
            ->where(function ($query) {
                // Condition for expiration: either null or in the future
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
            'attendedEvents' => $attendedEventsCount, 
            'evaluatedEvents' => $evaluatedEventsCount, 
            'eventsToEvaluate' => $eventsToEvaluateCount, 
            'unevaluatedEvents' => $unevaluatedEvents, 
            'notificationCount' => $totalNotificationCount, 
            'generalNotifications' => $generalNotifications, 
            'displayItems' => $displayItems, 
            'announcements' => $announcements, 
        ]);
    }

    /**
     * Helper function to prepare items for Upcoming Events/Holidays display.
     * (Same as before)
     */
    private function prepareDisplayItems($upcomingEvents, $holidays)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $displayHolidays = [];
        foreach ($holidays as $date => $name) {
             try {
                $holidayDate = Carbon::parse($date);
                $holidayMonth = $holidayDate->month;
                if ($holidayDate->year == $currentYear && ($holidayMonth == $currentMonth || $holidayMonth == $currentMonth + 1)) {
                    $displayHolidays[] = ['date' => $holidayDate, 'name' => $name, 'month' => $holidayDate->format('M'), 'day' => $holidayDate->format('d'), 'is_holiday' => true];
                }
             } catch (\Exception $e) {
                Log::error("Error parsing holiday date: $date - " . $e->getMessage());
             }
        }

        $allUpcomingItems = collect();
        foreach ($upcomingEvents as $event) {
            $eventDate = null; // Initialize
             if ($event->event_date instanceof Carbon) {
                 $eventDate = $event->event_date;
             } elseif (is_string($event->event_date)) {
                  try {
                      $eventDate = Carbon::parse($event->event_date);
                  } catch (\Exception $e) {
                      Log::error("Error parsing event date string for event ID {$event->id}: '{$event->event_date}' - " . $e->getMessage());
                      continue; // Skip this event if date is invalid
                  }
             } else {
                 Log::warning("Event date is not a Carbon instance or string for event ID {$event->id}");
                 continue; // Skip if format is unexpected
             }

             if ($eventDate) { // Proceed only if date was parsed correctly
                 $allUpcomingItems->push(['type' => 'event', 'date' => $eventDate, 'month' => $eventDate->format('M'), 'day' => $eventDate->format('d'), 'title' => $event->title, 'status' => 'Upcoming', 'is_holiday' => false, 'event' => $event]);
             }
        }
        foreach ($displayHolidays as $holiday) {
             $allUpcomingItems->push(['type' => 'holiday', 'date' => $holiday['date'], 'month' => $holiday['month'], 'day' => $holiday['day'], 'title' => $holiday['name'], 'status' => 'Holiday', 'is_holiday' => true, 'event' => null]);
        }

        return $allUpcomingItems->sortBy('date')->take(6);
    }

} // End of DashboardController class