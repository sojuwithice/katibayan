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

        // --- Current date/time ---
        $today = Carbon::today();
        $now = Carbon::now();

        // ====================================================
        // FIXED: GET UPCOMING EVENTS (Only FUTURE launched events)
        // ====================================================
        $upcomingEvents = Event::where('is_launched', true)
            ->where('barangay_id', $user->barangay_id)
            ->where(function($query) use ($today, $now) {
                // Events with date in future
                $query->where('event_date', '>', $today)
                    // OR events happening today but time is in future
                    ->orWhere(function($q) use ($today, $now) {
                        $q->where('event_date', $today)
                          ->where('event_time', '>', $now->format('H:i:s'));
                    });
            })
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(4) // Max 4 for slider
            ->get()
            ->map(function($event) use ($now) {
                $eventDate = Carbon::parse($event->event_date);
                $eventDateTime = $eventDate->copy();
                
                if ($event->event_time) {
                    try {
                        $timeParts = explode(':', $event->event_time);
                        $eventDateTime->setTime((int)$timeParts[0], (int)($timeParts[1] ?? 0), (int)($timeParts[2] ?? 0));
                    } catch (\Exception $e) {
                        Log::warning("Invalid time format for event {$event->id}: {$event->event_time}");
                    }
                }
                
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date,
                    'event_time' => $event->event_time,
                    'location' => $event->location,
                    'category' => $event->category,
                    'image' => $event->image,
                    'is_launched' => $event->is_launched,
                    'status' => $event->status,
                    'type' => 'event',
                    'formatted_date' => $eventDate->format('M d'),
                    'formatted_time' => $event->event_time ? Carbon::parse($event->event_time)->format('g:i A') : 'TBA',
                    'date_object' => $eventDate,
                    'datetime_object' => $eventDateTime,
                    'is_future' => $eventDateTime->gt($now)
                ];
            })
            ->filter(function($event) {
                return $event['is_future'];
            });

        // ====================================================
        // FIXED: GET UPCOMING PROGRAMS (Only FUTURE programs)
        // ====================================================
        $upcomingPrograms = Program::where('barangay_id', $user->barangay_id)
            ->where(function($query) use ($today, $now) {
                // Programs with date in future
                $query->where('event_date', '>', $today)
                    // OR programs happening today but time is in future
                    ->orWhere(function($q) use ($today, $now) {
                        $q->where('event_date', $today)
                          ->where('event_time', '>', $now->format('H:i:s'));
                    });
            })
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(4) // Max 4 for slider
            ->get()
            ->map(function($program) use ($now) {
                $programDate = Carbon::parse($program->event_date);
                $programDateTime = $programDate->copy();
                
                if ($program->event_time) {
                    try {
                        $timeParts = explode(':', $program->event_time);
                        $programDateTime->setTime((int)$timeParts[0], (int)($timeParts[1] ?? 0), (int)($timeParts[2] ?? 0));
                    } catch (\Exception $e) {
                        Log::warning("Invalid time format for program {$program->id}: {$program->event_time}");
                    }
                }
                
                return [
                    'id' => $program->id,
                    'title' => $program->title,
                    'description' => $program->description,
                    'event_date' => $program->event_date,
                    'event_end_date' => $program->event_end_date,
                    'event_time' => $program->event_time,
                    'location' => $program->location,
                    'category' => $program->category,
                    'display_image' => $program->display_image,
                    'registration_type' => $program->registration_type,
                    'link_source' => $program->link_source,
                    'type' => 'program',
                    'formatted_date' => $programDate->format('M d'),
                    'formatted_time' => $program->event_time ? Carbon::parse($program->event_time)->format('g:i A') : 'TBA',
                    'date_object' => $programDate,
                    'datetime_object' => $programDateTime,
                    'is_future' => $programDateTime->gt($now)
                ];
            })
            ->filter(function($program) {
                return $program['is_future'];
            });

        Log::info("=== SLIDER DATA ===");
        Log::info("Upcoming events found: " . $upcomingEvents->count());
        Log::info("Upcoming programs found: " . $upcomingPrograms->count());

        // ====================================================
        // PREPARE SLIDER ITEMS
        // ====================================================
        $sliderItems = collect();
        
        // Always add welcome slide as first
        $sliderItems->push([
            'type' => 'welcome',
            'data' => [
                'title' => 'Welcome',
                'user_name' => $user->given_name
            ],
            'sort_date' => Carbon::now()->subYears(100) // Very old, always first
        ]);
        
        // Add upcoming events to slider
        foreach ($upcomingEvents as $event) {
            $sliderItems->push([
                'type' => 'event',
                'data' => $event,
                'sort_date' => $event['datetime_object']
            ]);
        }
        
        // Add upcoming programs to slider
        foreach ($upcomingPrograms as $program) {
            $sliderItems->push([
                'type' => 'program',
                'data' => $program,
                'sort_date' => $program['datetime_object']
            ]);
        }
        
        // Sort by date (closest first)
        $sliderItems = $sliderItems->sortBy('sort_date');

        // If no upcoming events/programs, add a "no events" slide
        if ($upcomingEvents->count() === 0 && $upcomingPrograms->count() === 0) {
            $sliderItems->push([
                'type' => 'no_events',
                'data' => [
                    'title' => 'No Upcoming Activities',
                    'message' => 'Check back later for new events and programs!'
                ],
                'sort_date' => Carbon::now()->addYears(100) // Very future, always last
            ]);
        }

        // Limit to 5 slides max (including welcome and no_events)
        $sliderItems = $sliderItems->take(5);

        // ====================================================
        // EVALUATION PROGRESS
        // ====================================================
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $registeredProgramsCount = ProgramRegistration::where('user_id', $user->id)
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $totalActivities = $attendedEventsCount + $registeredProgramsCount;

        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('event_id')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $evaluatedProgramsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('program_id')
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $evaluatedActivities = $evaluatedEventsCount + $evaluatedProgramsCount;
        $activitiesToEvaluate = max(0, $totalActivities - $evaluatedActivities);

        // ====================================================
        // FIXED: UNEVALUATED ACTIVITIES FOR NOTIFICATIONS
        // ====================================================
        $unevaluatedEvents = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id)->latest();
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        $unevaluatedPrograms = Program::where('barangay_id', $user->barangay_id)
            ->whereHas('programRegistrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['programRegistrations' => function($query) use ($user) {
                $query->where('user_id', $user->id)->latest();
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        $unevaluatedActivities = collect();
        
        foreach ($unevaluatedEvents as $event) {
            $attendance = $event->attendances->first();
            $createdAt = $attendance ? $attendance->created_at : $event->created_at;
            
            $unevaluatedActivities->push([
                'id' => $event->id,
                'type' => 'event',
                'title' => $event->title,
                'notification_type' => 'evaluation_required',
                'attendance' => $attendance,
                'created_at' => $createdAt,
                'datetime_object' => $createdAt instanceof Carbon ? $createdAt : Carbon::parse($createdAt)
            ]);
        }
        
        foreach ($unevaluatedPrograms as $program) {
            $registration = $program->programRegistrations->first();
            $createdAt = $registration ? $registration->created_at : $program->created_at;
            
            $unevaluatedActivities->push([
                'id' => $program->id,
                'type' => 'program',
                'title' => $program->title,
                'notification_type' => 'evaluation_required',
                'registration' => $registration,
                'created_at' => $createdAt,
                'datetime_object' => $createdAt instanceof Carbon ? $createdAt : Carbon::parse($createdAt)
            ]);
        }

        // ====================================================
        // FIXED: COMBINE ALL NOTIFICATIONS WITH PROPER SORTING
        // ====================================================
        // Get general notifications from database
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10) // Get more initially for combining
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->data['title'] ?? $notification->title ?? 'Notification',
                    'message' => $notification->data['message'] ?? $notification->message ?? 'You have a new notification.',
                    'created_at' => $notification->created_at,
                    'datetime_object' => $notification->created_at,
                    'is_read' => $notification->is_read,
                    'notification_type' => 'general',
                    'source' => 'database'
                ];
            });

        // Convert unevaluated activities to notification format
        $evaluationNotifications = $unevaluatedActivities->map(function($activity) {
            return [
                'id' => 'eval_' . $activity['id'] . '_' . $activity['type'],
                'type' => 'evaluation_required',
                'title' => ucfirst($activity['type']) . ' Evaluation Required',
                'message' => 'Please evaluate "' . $activity['title'] . '"',
                'created_at' => $activity['created_at'],
                'datetime_object' => $activity['datetime_object'],
                'is_read' => 0, // Always unread until evaluated
                'notification_type' => 'evaluation_required',
                'source' => 'system',
                'activity_id' => $activity['id'],
                'activity_type' => $activity['type']
            ];
        });

        // Combine all notifications
        $allNotifications = $generalNotifications->concat($evaluationNotifications);

        // Sort by datetime_object (latest first)
        $allNotifications = $allNotifications->sortByDesc('datetime_object')->values();

        // Limit to 7 notifications for display (or adjust as needed)
        $allNotifications = $allNotifications->take(7);

        // Calculate unread count
        $unreadNotificationCount = $allNotifications->where('is_read', 0)->count();

        // Separate for blade template if needed
        $displayNotifications = $allNotifications;
        $displayUnevaluatedActivities = $unevaluatedActivities;

        // Upcoming Events & Programs for Display (sidebar)
        $displayItems = $this->prepareDisplayItems($upcomingEvents, $upcomingPrograms);

        // Attendance Percentage
        $totalPastEvents = Event::where('is_launched', true)
            ->where('event_date', '<', $today)
            ->where('barangay_id', $user->barangay_id)
            ->count();

        $attendancePercentage = $totalPastEvents > 0 ? round(($attendedEventsCount / $totalPastEvents) * 100) : 0;

        // Announcements
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
            
            // Evaluation
            'totalActivities' => $totalActivities,
            'evaluatedActivities' => $evaluatedActivities,
            'activitiesToEvaluate' => $activitiesToEvaluate,
            
            // FIXED: NOTIFICATIONS - Now combined and sorted properly
            'unevaluatedActivities' => $displayUnevaluatedActivities,
            'notificationCount' => $unreadNotificationCount,
            'generalNotifications' => $displayNotifications, // This now includes ALL notifications
            
            // Display
            'displayItems' => $displayItems,
            'announcements' => $announcements,

            // SLIDER ITEMS
            'sliderItems' => $sliderItems,
            'upcomingEvents' => $upcomingEvents,
            'upcomingPrograms' => $upcomingPrograms,
        ]);
    }

    private function prepareDisplayItems($upcomingEvents, $upcomingPrograms)
    {
        $holidays = [
            '2025-01-01' => 'New Year\'s Day', '2025-04-09' => 'Araw ng Kagitingan', 
            '2025-04-17' => 'Maundy Thursday', '2025-04-18' => 'Good Friday', 
            '2025-05-01' => 'Labor Day', '2025-06-06' => 'Eid\'l Fitr',
            '2025-06-12' => 'Independence Day', '2025-08-25' => 'National Heroes Day', 
            '2025-11-30' => 'Bonifacio Day', '2025-12-25' => 'Christmas Day', 
            '2025-12-30' => 'Rizal Day'
        ];
        
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
            if ($event['date_object'] instanceof Carbon) {
                $eventDate = $event['date_object'];
            } elseif (is_string($event['event_date'])) {
                try {
                    $eventDate = Carbon::parse($event['event_date']);
                } catch (\Exception $e) {
                    Log::error("Error parsing event date string for event ID {$event['id']}: '{$event['event_date']}' - " . $e->getMessage());
                    continue;
                }
            }

            if ($eventDate) {
                $allUpcomingItems->push([
                    'type' => 'event', 
                    'date' => $eventDate, 
                    'month' => $eventDate->format('M'), 
                    'day' => $eventDate->format('d'), 
                    'title' => $event['title'], 
                    'status' => 'Upcoming Event', 
                    'is_holiday' => false
                ]);
            }
        }

        // Add programs
        foreach ($upcomingPrograms as $program) {
            $programDate = null;
            if ($program['date_object'] instanceof Carbon) {
                $programDate = $program['date_object'];
            } elseif (is_string($program['event_date'])) {
                try {
                    $programDate = Carbon::parse($program['event_date']);
                } catch (\Exception $e) {
                    Log::error("Error parsing program date string for program ID {$program['id']}: '{$program['event_date']}' - " . $e->getMessage());
                    continue;
                }
            }

            if ($programDate) {
                $allUpcomingItems->push([
                    'type' => 'program', 
                    'date' => $programDate, 
                    'month' => $programDate->format('M'), 
                    'day' => $programDate->format('d'), 
                    'title' => $program['title'], 
                    'status' => 'Upcoming Program', 
                    'is_holiday' => false
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
                'is_holiday' => true
            ]);
        }

        return $allUpcomingItems->sortBy('date')->take(6);
    }

    public function showCommittee()
{
    $chairperson = \App\Models\User::where('role', 'sk_chairperson')->first();

$skMembers = \App\Models\User::whereNotNull('sk_role')
    ->where('sk_role', '!=', '')
    ->when($chairperson, function ($q) use ($chairperson) {
        return $q->where('id', '!=', $chairperson->id);
    })
    ->get();

}

}