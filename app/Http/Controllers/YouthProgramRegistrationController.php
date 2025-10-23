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
     * Show registration list for a specific program - ENHANCED WITH ATTENDANCE TRACKING
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
                ->map(function($registration) {
                    $age = null;
                    if ($registration->user->date_of_birth) {
                        $age = Carbon::parse($registration->user->date_of_birth)->age;
                    }
                    
                    // Get all registration data including enhanced user profile and custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    $userProfile = $registrationData['user_profile'] ?? [];
                    $customFields = $registrationData['custom_fields'] ?? [];
                    
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
                'attendedCount'
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
     * Update attendance status
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
     * Get program registration details with all submitted data (for AJAX/modal)
     */
    public function getProgramRegistrations($programId)
    {
        try {
            $user = Auth::user();
            
            $program = Program::where('id', $programId)
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();

            $registrations = ProgramRegistration::with(['user' => function($query) {
                    $query->select('id', 'given_name', 'middle_name', 'last_name', 'suffix', 'email', 'contact_no', 'date_of_birth', 'barangay_id');
                }])
                ->with(['user.barangay' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('program_id', $programId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($registration) {
                    $age = null;
                    if ($registration->user->date_of_birth) {
                        $age = Carbon::parse($registration->user->date_of_birth)->age;
                    }
                    
                    // Get all registration data including enhanced user profile and custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    $userProfile = $registrationData['user_profile'] ?? [];
                    $customFields = $registrationData['custom_fields'] ?? [];
                    
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
                    'event_time' => $program->event_time,
                    'category' => $program->category,
                    'total_registrations' => $registrations->count()
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

            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $totalRegistrations,
                    'attended' => $attendedCount,
                    'absent' => $absentCount,
                    'attendance_rate' => $totalRegistrations > 0 ? round(($attendedCount / $totalRegistrations) * 100, 2) : 0
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