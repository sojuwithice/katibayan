<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Mark attendance using QR code or manual passcode
     */
    public function markAttendance(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not authenticated'
                ], 401);
            }

            $passcode = $request->input('passcode');
            if (!$passcode) {
                return response()->json([
                    'success' => false,
                    'error' => 'Passcode is required'
                ], 400);
            }

            // 🔍 Find event
            $event = Event::where('passcode', $passcode)
                        ->where('is_launched', true)
                        ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid passcode or event not launched'
                ], 404);
            }

            // 🚫 Check if already attended
            $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('event_id', $event->id)
                                        ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have already attended this event'
                ], 409);
            }

            // 📅 Check if event is for today
            $today = Carbon::today();
            $eventDate = Carbon::parse($event->event_date);

            if (!$eventDate->isSameDay($today)) {
                return response()->json([
                    'success' => false,
                    'error' => 'This event is not scheduled for today'
                ], 400);
            }

            // 🧩 Build full name and compute age using correct field names
            $fullnameParts = array_filter([
                $user->given_name ?? '',
                $user->middle_name ?? '',
                $user->last_name ?? '',
                $user->suffix ?? ''
            ]);
            $fullname = implode(' ', $fullnameParts);
            $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : null;

            // ✅ Fixed Attendance creation
            $attendance = new Attendance();

            $attendance->user_id        = $user->id;
            $attendance->event_id       = $event->id;
            $attendance->attended_at    = now();
            $attendance->passcode_used  = $passcode;
            $attendance->status         = 'Attended';
            $attendance->date           = now()->format('Y-m-d');
            $attendance->time           = now()->format('H:i:s');

            // User details - using correct field names
            $attendance->account_number = $user->account_number ?? '-';
            $attendance->fullname       = $fullname ?: '-';
            $attendance->age            = $age ?: null;
            $attendance->purok          = $user->purok_zone ?? '-';
            $attendance->role           = $user->role ?? '-';

            $attendance->save();

            // Optional: log to debug
            Log::info('Attendance saved', $attendance->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully!',
                'event' => [
                    'title' => $event->title,
                    'date'  => $event->event_date->format('F j, Y'),
                    'time'  => $event->formatted_time,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark attendance'
            ], 500);
        }
    }

    /**
     * Get attendance records for current user
     */
    public function getUserAttendances(): JsonResponse
    {
        try {
            $attendances = Attendance::with(['user', 'event'])
                ->orderBy('attended_at', 'desc')
                ->get()
                ->map(function ($attendance) {
                    $user = $attendance->user;

                    // Build full name properly using correct field names
                    $fullnameParts = array_filter([
                        $user->given_name ?? '',
                        $user->middle_name ?? '',
                        $user->last_name ?? '',
                        $user->suffix ?? ''
                    ]);
                    $fullname = implode(' ', $fullnameParts) ?: '-';

                    return [
                        'status'         => $attendance->status ?? 'Attended',
                        'date'           => $attendance->date ?? $attendance->attended_at->format('Y-m-d'),
                        'time'           => $attendance->time ?? $attendance->attended_at->format('H:i:s'),
                        'account_number' => $user->account_number ?? '-',
                        'name'           => $fullname,
                        'age'            => $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : '-',
                        'purok'          => $user->purok_zone ?? '-',
                        'role'           => $user->role ?? '-',
                    ];
                });

            return response()->json([
                'success' => true,
                'attendances' => $attendances
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching attendances: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch attendance records'
            ], 500);
        }
    }

    public function getAllAttendances()
    {
        try {
            $attendances = Attendance::orderBy('attended_at', 'desc')->get([
                'status',
                'date',
                'time',
                'account_number',
                'fullname',
                'age',
                'purok',
                'role'
            ]);

            return response()->json([
                'success' => true,
                'attendances' => $attendances
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching attendance data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load attendance data'], 500);
        }
    }

    public function myAttendance()
{
    $user = Auth::user();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'error' => 'User not authenticated'
        ], 401);
    }

    $attendances = Attendance::with('user')
        ->where('user_id', $user->id)
        ->get()
        ->map(function ($attendance) {
            $u = $attendance->user;

            if (!$u) {
                return null;
            }

            // 🧩 Build full name properly using correct field names
            $fullnameParts = array_filter([
                $u->given_name ?? '',
                $u->middle_name ?? '',
                $u->last_name ?? '',
                $u->suffix ?? ''
            ]);
            $fullname = implode(' ', $fullnameParts);

            // 🕓 Compute age from date_of_birth
            $age = $u->date_of_birth ? Carbon::parse($u->date_of_birth)->age : '-';

            return [
                'status'          => $attendance->status ?? 'Attended',
                'date'            => $attendance->attended_at ? $attendance->attended_at->format('Y-m-d') : '-',
                'time'            => $attendance->attended_at ? $attendance->attended_at->format('h:i A') : '-',
                'account_number'  => $u->account_number ?? '-',
                'name'            => $fullname ?: '-',
                'age'             => $age,
                'purok'           => $u->purok_zone ?? '-',
                'role'            => $u->role ?? '-',
            ];
        })
        ->filter();

    return response()->json([
        'success' => true,
        'attendances' => $attendances,
    ]);
}
    public function showAttendancePage(Request $request)
    {
        $eventId = $request->query('event_id');
        $event = Event::find($eventId);

        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }

        return view('attendancepage', compact('event'));
    }

    public function getEventAttendances(Request $request): JsonResponse
    {
        $eventId = $request->query('event_id');

        try {
            $attendances = Attendance::where('event_id', $eventId)
                ->with('user')
                ->orderBy('attended_at', 'desc')
                ->get()
                ->map(function ($a) {
                    $u = $a->user;
                    if (!$u) return null;

                    $fullnameParts = array_filter([
                        $u->given_name ?? '',
                        $u->middle_name ?? '',
                        $u->last_name ?? '',
                        $u->suffix ?? ''
                    ]);
                    $fullname = implode(' ', $fullnameParts) ?: '-';

                    return [
                        'status' => $a->status ?? 'Attended',
                        'date' => $a->date ?? $a->attended_at->format('Y-m-d'),
                        'time' => $a->time ?? $a->attended_at->format('H:i:s'),
                        'account_number' => $u->account_number ?? '-',
                        'name' => $fullname,
                        'age' => $u->date_of_birth ? Carbon::parse($u->date_of_birth)->age : '-',
                        'purok' => $u->purok_zone ?? '-',
                        'role' => $u->role ?? '-',
                    ];
                })
                ->filter();

            return response()->json([
                'success' => true,
                'attendances' => $attendances
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching event attendances: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch attendance records'
            ], 500);
        }
    }

    /**
     * Show attendees for a specific event
     */
    public function showAttendees(Request $request)
    {
        try {
            $eventId = $request->query('event_id');
            
            if (!$eventId) {
                return redirect()->route('youth-participation')->with('error', 'No event specified.');
            }

            // Get the event details
            $event = Event::find($eventId);
            
            if (!$event) {
                return redirect()->route('youth-participation')->with('error', 'Event not found.');
            }

            // Get attendees for this event
            $attendances = Attendance::where('event_id', $eventId)
                ->with('user')
                ->orderBy('attended_at', 'desc')
                ->get();

            return view('list-of-attendees', [
                'event' => $event,
                'attendances' => $attendances
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching attendees: ' . $e->getMessage());
            return redirect()->route('youth-participation')->with('error', 'Failed to load attendees.');
        }
    }
}