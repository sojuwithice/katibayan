<?php
// app/Http/Controllers/YouthProgramRegistrationController.php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // If a specific program is requested, get its registrations
        $selectedProgram = null;
        $programRegistrations = collect();
        
        if ($request->has('program_id')) {
            $selectedProgram = Program::where('id', $request->program_id)
                ->where('barangay_id', $user->barangay_id)
                ->first();
                
            if ($selectedProgram) {
                $programRegistrations = ProgramRegistration::with('user')
                    ->where('program_id', $selectedProgram->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('youth-program-registration', compact(
            'user',
            'age',
            'roleBadge',
            'currentMonthPrograms',
            'upcomingProgramsByMonth',
            'selectedProgram',
            'programRegistrations'
        ));
    }

    /**
     * Get program registration details with all submitted data
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
                    
                    // Get all registration data including custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'user_name' => $registration->user->given_name . ' ' . 
                                      ($registration->user->middle_name ? $registration->user->middle_name . ' ' : '') . 
                                      $registration->user->last_name . 
                                      ($registration->user->suffix ? ' ' . $registration->user->suffix : ''),
                        'email' => $registration->user->email,
                        'contact_no' => $registration->user->contact_no,
                        'age' => $age,
                        'barangay' => $registration->user->barangay->name ?? 'N/A',
                        'motivation' => $registration->motivation,
                        'expectations' => $registration->expectations,
                        'special_requirements' => $registration->special_requirements,
                        'status' => $registration->status,
                        'registered_at' => $registration->created_at->format('M d, Y g:i A'),
                        'registration_data' => $registrationData,
                        'custom_fields' => isset($registrationData['custom_fields']) ? $registrationData['custom_fields'] : []
                    ];
                });

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
                'registrations' => $registrations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations: ' . $e->getMessage()
            ], 500);
        }
    }
}