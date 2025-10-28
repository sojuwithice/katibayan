<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Announcement;
use App\Models\CertificateSchedule; 
use App\Models\CertificateRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
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
            return redirect()->route('login');
        }

        // --- BASIC USER INFO ---
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Member';
        Log::info("Loading dashboard for user ID: {$user->id}, Barangay ID: {$user->barangay_id}");

        // --- NOTIFICATIONS (TOP DROPDOWN) ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // --- EVALUATION PROGRESS ---
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $eventsRequiringEvaluation = Event::where('is_launched', true)
            ->where('barangay_id', $user->barangay_id)
            ->whereHas('attendances', function ($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })->count();

        $eventsToEvaluateCount = max(0, $eventsRequiringEvaluation - $evaluatedEventsCount);

        // --- UNEVALUATED EVENTS ---
        $unevaluatedEvents = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->whereHas('attendances', function ($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('event_date', 'desc')
            ->get();

        $totalNotificationCount = $unreadNotificationCount + $unevaluatedEvents->count();

        // --- UPCOMING EVENTS + HOLIDAYS ---
        $upcomingEvents = Event::where('is_launched', true)
            ->where('event_date', '>=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(6)
            ->get();

        $holidays = [
            '2025-01-01' => 'New Year\'s Day', '2025-04-09' => 'Araw ng Kagitingan',
            '2025-04-17' => 'Maundy Thursday', '2025-04-18' => 'Good Friday',
            '2025-05-01' => 'Labor Day', '2025-06-06' => 'Eid\'l Fitr',
            '2025-06-12' => 'Independence Day', '2025-08-25' => 'National Heroes Day',
            '2025-11-30' => 'Bonifacio Day', '2025-12-25' => 'Christmas Day',
            '2025-12-30' => 'Rizal Day'
        ];

        $displayItems = $this->prepareDisplayItems($upcomingEvents, $holidays);

        // --- ATTENDANCE PERCENTAGE ---
        $totalPastEvents = Event::where('is_launched', true)
            ->where('event_date', '<=', Carbon::today())
            ->where('barangay_id', $user->barangay_id)
            ->count();

        $attendancePercentage = $totalPastEvents > 0
            ? round(($attendedEventsCount / $totalPastEvents) * 100)
            : 0;

        // --- ANNOUNCEMENTS LOGIC ---
        $hasRequestedBefore = CertificateRequest::where('user_id', $user->id)->exists();

        // Active & same barangay OR global announcements
        $announcementsQuery = Announcement::where(function ($q) use ($user) {
            $q->where('barangay_id', $user->barangay_id)
              ->orWhereNull('barangay_id'); // include global
        })->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });

        if (!$hasRequestedBefore) {
            // NEW USER: show only general announcements
            $announcementsQuery->where(function ($q) {
                $q->where('type', 'general')
                  ->orWhereNull('type');
            });
        }

        $announcements = $announcementsQuery->latest()->get();

        // --- RETURN TO VIEW ---
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
     * Prepare items for the Upcoming Events / Holidays display.
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

                if (
                    $holidayDate->year == $currentYear &&
                    ($holidayMonth == $currentMonth || $holidayMonth == $currentMonth + 1)
                ) {
                    $displayHolidays[] = [
                        'date' => $holidayDate,
                        'name' => $name,
                        'month' => $holidayDate->format('M'),
                        'day' => $holidayDate->format('d'),
                        'is_holiday' => true,
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Error parsing holiday date: $date - " . $e->getMessage());
            }
        }

        $allUpcomingItems = collect();

        // Add Events
        foreach ($upcomingEvents as $event) {
            try {
                $eventDate = Carbon::parse($event->event_date);
                $allUpcomingItems->push([
                    'type' => 'event',
                    'date' => $eventDate,
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'title' => $event->title,
                    'status' => 'Upcoming',
                    'is_holiday' => false,
                    'event' => $event,
                ]);
            } catch (\Exception $e) {
                Log::error("Invalid event date for event ID {$event->id}: " . $e->getMessage());
            }
        }

        // Add Holidays
        foreach ($displayHolidays as $holiday) {
            $allUpcomingItems->push([
                'type' => 'holiday',
                'date' => $holiday['date'],
                'month' => $holiday['month'],
                'day' => $holiday['day'],
                'title' => $holiday['name'],
                'status' => 'Holiday',
                'is_holiday' => true,
                'event' => null,
            ]);
        }

        return $allUpcomingItems->sortBy('date')->take(6);
    }

    public function markAsRead($id)
{
    $notification = \App\Models\Notification::find($id);
    
    if ($notification && $notification->user_id === auth()->id()) {
        $notification->update(['is_read' => 1]);
    }

    return response()->json(['success' => true]);
}

}
