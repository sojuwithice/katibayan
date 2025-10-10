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
            Log::info("Attendance attempt for user {$user->id} with passcode: {$passcode}");

            if (!$passcode) {
                return response()->json([
                    'success' => false,
                    'error' => 'Passcode is required'
                ], 400);
            }

            // Find event by passcode
            $event = Event::where('passcode', $passcode)
                        ->where('is_launched', true)
                        ->first();

            if (!$event) {
                Log::warning("Invalid passcode attempt: {$passcode}");
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid passcode or event not launched'
                ], 404);
            }

            // Check if user already attended this event
            $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('event_id', $event->id)
                                        ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have already attended this event'
                ], 409);
            }

            // Check if event is today
            $today = Carbon::today();
            $eventDate = Carbon::parse($event->event_date);
            
            if (!$eventDate->isSameDay($today)) {
                return response()->json([
                    'success' => false,
                    'error' => 'This event is not scheduled for today'
                ], 400);
            }

            // Create attendance record
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'attended_at' => now(),
                'passcode_used' => $passcode,
            ]);

            Log::info("Attendance marked successfully for user {$user->id} at event {$event->id}");

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully!',
                'event' => [
                    'title' => $event->title,
                    'date' => $event->event_date->format('F j, Y'),
                    'time' => $event->formatted_time,
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
            $user = Auth::user();
            $attendances = Attendance::with('event')
                ->where('user_id', $user->id)
                ->orderBy('attended_at', 'desc')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'event_title' => $attendance->event->title,
                        'event_date' => $attendance->event->event_date->format('F j, Y'),
                        'event_time' => $attendance->event->formatted_time,
                        'attended_at' => $attendance->attended_at->format('F j, Y g:i A'),
                        'location' => $attendance->event->location,
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
}