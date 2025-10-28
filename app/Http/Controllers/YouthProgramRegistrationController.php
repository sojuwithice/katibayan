<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class YouthProgramRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate age properly
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth); 
            $age = $birthdate->age;
        }

        // Set role badge
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        // Get programs for the current month
        $currentMonthPrograms = Program::where('barangay_id', $user->barangay_id)
            ->whereMonth('event_date', now()->month)
            ->whereYear('event_date', now()->year)
            ->where('event_date', '>=', now()->format('Y-m-d'))
            ->orderBy('event_date')
            ->get();

        // Get upcoming programs (future months)
        $upcomingPrograms = Program::where('barangay_id', $user->barangay_id)
            ->where('event_date', '>', now()->endOfMonth())
            ->orderBy('event_date')
            ->get();

        // Group upcoming programs by month
        $upcomingProgramsByMonth = $upcomingPrograms->groupBy(function($program) {
            return Carbon::parse($program->event_date)->format('F Y');
        });

        return view('youth-program-registration', compact(
            'user',
            'age',
            'roleBadge',
            'currentMonthPrograms',
            'upcomingProgramsByMonth'
        ));
    }

    /**
     * Show registration list for a specific program - ENHANCED WITH DAILY ATTENDANCE TRACKING
     */
    public function showRegistrationList($programId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate age for profile
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth); 
            $age = $birthdate->age;
        }

        // Set role badge
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        try {
            // Get the program with details
            $program = Program::where('id', $programId)
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();

            // Calculate total program days
            $startDate = Carbon::parse($program->event_date);
            $endDate = $program->event_end_date ? Carbon::parse($program->event_end_date) : $startDate;
            $totalDays = $startDate->diffInDays($endDate) + 1;

            // Get all registrations for this program with enhanced data
            $registrations = ProgramRegistration::with(['user' => function($query) {
                    $query->select('id', 'given_name', 'middle_name', 'last_name', 'suffix', 'email', 'contact_no', 'date_of_birth', 'barangay_id');
                }])
                ->with(['user.barangay' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('program_id', $programId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($registration) use ($totalDays) {
                    $age = null;
                    if ($registration->user->date_of_birth) {
                        $age = Carbon::parse($registration->user->date_of_birth)->age;
                    }
                    
                    // Get all registration data including enhanced user profile and custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    $userProfile = $registrationData['user_profile'] ?? [];
                    $customFields = $registrationData['custom_fields'] ?? [];
                    
                    // Calculate daily attendance
                    $attendanceDays = $registration->attendance_days ?: [];
                    $presentDays = 0;
                    
                    if (is_array($attendanceDays)) {
                        $presentDays = count(array_filter($attendanceDays, function($attended) {
                            return $attended === true || $attended === 'true';
                        }));
                    }
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'user_name' => $userProfile['full_name'] ?? 
                                      $registration->user->given_name . ' ' . 
                                      ($registration->user->middle_name ? $registration->user->middle_name . ' ' : '') . 
                                      $registration->user->last_name . 
                                      ($registration->user->suffix ? ' ' . $registration->user->suffix : ''),
                        'email' => $userProfile['email'] ?? $registration->user->email,
                        'contact_no' => $userProfile['contact_no'] ?? $registration->user->contact_no,
                        'age' => $userProfile['age'] ?? $age,
                        'barangay' => $userProfile['barangay'] ?? ($registration->user->barangay->name ?? 'N/A'),
                        'attended' => $registration->attended ?? false,
                        'attendance_days' => $attendanceDays,
                        'present_days' => $presentDays,
                        'total_days' => $totalDays,
                        'registered_at' => $registration->created_at->format('M d, Y g:i A'),
                        'attended_at' => $registration->attended_at ? $registration->attended_at->format('M d, Y g:i A') : null,
                        'registration_data' => $registrationData,
                        'custom_fields' => $customFields,
                        'user_profile' => $userProfile
                    ];
                });

            // Calculate attendance count
            $attendedCount = $registrations->where('attended', true)->count();

            return view('youth-registration-list', compact(
                'user',
                'age',
                'roleBadge',
                'program',
                'registrations',
                'attendedCount',
                'totalDays'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading registration list: ' . $e->getMessage());
            
            // Return with empty registrations if program not found
            return view('youth-registration-list', compact(
                'user',
                'age',
                'roleBadge'
            ))->with('error', 'Program not found or you do not have access to it.');
        }
    }

    /**
     * Update overall attendance status
     */
    public function updateAttendance(Request $request, $registrationId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'attended' => 'required|boolean'
            ]);

            $registration = ProgramRegistration::where('id', $registrationId)
                ->firstOrFail();

            // Update attendance
            $registration->update([
                'attended' => $request->attended,
                'attended_at' => $request->attended ? now() : null,
                'marked_by_user_id' => $user->id
            ]);

            // Get updated registration with user data for response
            $updatedRegistration = ProgramRegistration::with(['user' => function($query) {
                    $query->select('id', 'given_name', 'middle_name', 'last_name', 'suffix', 'email', 'contact_no', 'date_of_birth', 'barangay_id');
                }])
                ->with(['user.barangay' => function($query) {
                    $query->select('id', 'name');
                }])
                ->find($registrationId);

            $age = null;
            if ($updatedRegistration->user->date_of_birth) {
                $age = Carbon::parse($updatedRegistration->user->date_of_birth)->age;
            }

            $registrationData = $updatedRegistration->registration_data ? json_decode($updatedRegistration->registration_data, true) : [];
            $userProfile = $registrationData['user_profile'] ?? [];

            $formattedRegistration = [
                'id' => $updatedRegistration->id,
                'reference_id' => $updatedRegistration->reference_id,
                'user_name' => $userProfile['full_name'] ?? 
                              $updatedRegistration->user->given_name . ' ' . 
                              ($updatedRegistration->user->middle_name ? $updatedRegistration->user->middle_name . ' ' : '') . 
                              $updatedRegistration->user->last_name . 
                              ($updatedRegistration->user->suffix ? ' ' . $updatedRegistration->user->suffix : ''),
                'email' => $userProfile['email'] ?? $updatedRegistration->user->email,
                'contact_no' => $userProfile['contact_no'] ?? $updatedRegistration->user->contact_no,
                'age' => $userProfile['age'] ?? $age,
                'barangay' => $userProfile['barangay'] ?? ($updatedRegistration->user->barangay->name ?? 'N/A'),
                'attended' => $updatedRegistration->attended,
                'registered_at' => $updatedRegistration->created_at->format('M d, Y g:i A'),
                'attended_at' => $updatedRegistration->attended_at ? $updatedRegistration->attended_at->format('M d, Y g:i A') : null,
            ];

            return response()->json([
                'success' => true,
                'message' => $request->attended ? 'Attendance marked as present' : 'Attendance marked as absent',
                'registration' => $formattedRegistration
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update daily attendance for a registration
     */
    public function updateDailyAttendance(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'registration_id' => 'required|exists:program_registrations,id',
                'attendance_data' => 'required|array',
                'present_count' => 'required|integer',
                'total_days' => 'required|integer'
            ]);

            $registration = ProgramRegistration::where('id', $request->registration_id)
                ->firstOrFail();

            // Update attendance_days JSON field
            $attendanceData = [];
            foreach ($request->attendance_data as $day => $attended) {
                $attendanceData[$day] = filter_var($attended, FILTER_VALIDATE_BOOLEAN);
            }

            // FIXED: Update all attendance fields including marked_by_user_id
            $registration->update([
                'attendance_days' => $attendanceData, // This will be automatically cast to JSON
                'attended' => $request->present_count > 0, // Mark as attended if at least one day present
                'attended_at' => $request->present_count > 0 ? now() : null,
                'marked_by_user_id' => $user->id
            ]);

            Log::info('Daily attendance updated successfully', [
                'registration_id' => $request->registration_id,
                'attendance_data' => $attendanceData,
                'present_count' => $request->present_count,
                'marked_by_user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Daily attendance updated successfully',
                'present_count' => $request->present_count,
                'total_days' => $request->total_days,
                'attendance_data' => $attendanceData
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating daily attendance: ' . $e->getMessage());
            Log::error('Request data: ', $request->all());
            return response()->json([
                'success' => false,
                'message' => 'Error updating daily attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily attendance data for a specific registration
     */
    public function getDailyAttendance($registrationId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $registration = ProgramRegistration::with(['program'])
                ->where('id', $registrationId)
                ->firstOrFail();

            // Verify the program belongs to user's barangay
            if ($registration->program->barangay_id !== $user->barangay_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            // Calculate total program days
            $startDate = Carbon::parse($registration->program->event_date);
            $endDate = $registration->program->event_end_date ? 
                Carbon::parse($registration->program->event_end_date) : $startDate;
            $totalDays = $startDate->diffInDays($endDate) + 1;

            // Get attendance data - use the casted array directly
            $attendanceDays = $registration->attendance_days ?: [];

            // Generate day labels
            $dayLabels = [];
            $currentDate = $startDate->copy();
            for ($i = 1; $i <= $totalDays; $i++) {
                $dayKey = "day_{$i}";
                $dayLabels["day_{$i}"] = [
                    'label' => "Day {$i}",
                    'date' => $currentDate->format('M d, Y'),
                    'attended' => $attendanceDays["day_{$i}"] ?? false
                ];
                $currentDate->addDay();
            }

            // Calculate present count
            $presentCount = count(array_filter($attendanceDays, function($attended) {
                return $attended === true || $attended === 'true';
            }));

            Log::info('Daily attendance data fetched successfully', [
                'registration_id' => $registrationId,
                'total_days' => $totalDays,
                'present_count' => $presentCount,
                'attendance_data' => $attendanceDays
            ]);

            return response()->json([
                'success' => true,
                'registration' => [
                    'id' => $registration->id,
                    'reference_id' => $registration->reference_id,
                    'user_name' => $registration->user->given_name . ' ' . 
                                  ($registration->user->middle_name ? $registration->user->middle_name . ' ' : '') . 
                                  $registration->user->last_name . 
                                  ($registration->user->suffix ? ' ' . $registration->user->suffix : ''),
                    'email' => $registration->user->email,
                    'total_days' => $totalDays
                ],
                'attendance_data' => $attendanceDays,
                'day_labels' => $dayLabels,
                'present_count' => $presentCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching daily attendance: ' . $e->getMessage());
            Log::error('Registration ID: ' . $registrationId);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching daily attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get program registration details with all submitted data (for AJAX/modal)
     */
    public function getProgramRegistrations($programId)
    {
        try {
            $user = Auth::user();
            
            $program = Program::where('id', $programId)
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();

            // Calculate total program days
            $startDate = Carbon::parse($program->event_date);
            $endDate = $program->event_end_date ? Carbon::parse($program->event_end_date) : $startDate;
            $totalDays = $startDate->diffInDays($endDate) + 1;

            $registrations = ProgramRegistration::with(['user' => function($query) {
                    $query->select('id', 'given_name', 'middle_name', 'last_name', 'suffix', 'email', 'contact_no', 'date_of_birth', 'barangay_id');
                }])
                ->with(['user.barangay' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('program_id', $programId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($registration) use ($totalDays) {
                    $age = null;
                    if ($registration->user->date_of_birth) {
                        $age = Carbon::parse($registration->user->date_of_birth)->age;
                    }
                    
                    // Get all registration data including enhanced user profile and custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    $userProfile = $registrationData['user_profile'] ?? [];
                    $customFields = $registrationData['custom_fields'] ?? [];
                    
                    // Calculate daily attendance
                    $attendanceDays = $registration->attendance_days ?: [];
                    $presentDays = count(array_filter($attendanceDays, function($attended) {
                        return $attended === true || $attended === 'true';
                    }));
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'user_name' => $userProfile['full_name'] ?? 
                                      $registration->user->given_name . ' ' . 
                                      ($registration->user->middle_name ? $registration->user->middle_name . ' ' : '') . 
                                      $registration->user->last_name . 
                                      ($registration->user->suffix ? ' ' . $registration->user->suffix : ''),
                        'email' => $userProfile['email'] ?? $registration->user->email,
                        'contact_no' => $userProfile['contact_no'] ?? $registration->user->contact_no,
                        'age' => $userProfile['age'] ?? $age,
                        'barangay' => $userProfile['barangay'] ?? ($registration->user->barangay->name ?? 'N/A'),
                        'attended' => $registration->attended ?? false,
                        'attendance_days' => $attendanceDays,
                        'present_days' => $presentDays,
                        'total_days' => $totalDays,
                        'registered_at' => $registration->created_at->format('M d, Y g:i A'),
                        'attended_at' => $registration->attended_at ? $registration->attended_at->format('M d, Y g:i A') : null,
                        'registration_data' => $registrationData,
                        'custom_fields' => $customFields,
                        'user_profile' => $userProfile
                    ];
                });

            // Calculate attendance count
            $attendedCount = $registrations->where('attended', true)->count();

            return response()->json([
                'success' => true,
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'event_date' => $program->event_date,
                    'event_end_date' => $program->event_end_date,
                    'event_time' => $program->event_time,
                    'category' => $program->category,
                    'total_registrations' => $registrations->count(),
                    'total_days' => $totalDays
                ],
                'registrations' => $registrations,
                'attended_count' => $attendedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching program registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance statistics for a program
     */
    public function getAttendanceStats($programId)
    {
        try {
            $user = Auth::user();
            
            $program = Program::where('id', $programId)
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();

            $totalRegistrations = ProgramRegistration::where('program_id', $programId)->count();
            $attendedCount = ProgramRegistration::where('program_id', $programId)->where('attended', true)->count();
            $absentCount = $totalRegistrations - $attendedCount;

            // Calculate daily attendance statistics
            $dailyStats = [];
            $startDate = Carbon::parse($program->event_date);
            $endDate = $program->event_end_date ? Carbon::parse($program->event_end_date) : $startDate;
            $totalDays = $startDate->diffInDays($endDate) + 1;

            for ($day = 1; $day <= $totalDays; $day++) {
                $dayKey = "day_{$day}";
                $dayPresentCount = ProgramRegistration::where('program_id', $programId)
                    ->whereRaw("JSON_EXTRACT(attendance_days, '$.\"{$dayKey}\"') = true")
                    ->count();
                
                $dailyStats[] = [
                    'day' => $day,
                    'present_count' => $dayPresentCount,
                    'attendance_rate' => $totalRegistrations > 0 ? 
                        round(($dayPresentCount / $totalRegistrations) * 100, 2) : 0
                ];
            }

            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $totalRegistrations,
                    'attended' => $attendedCount,
                    'absent' => $absentCount,
                    'attendance_rate' => $totalRegistrations > 0 ? 
                        round(($attendedCount / $totalRegistrations) * 100, 2) : 0,
                    'daily_stats' => $dailyStats
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching attendance stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance statistics'
            ], 500);
        }
    }
}