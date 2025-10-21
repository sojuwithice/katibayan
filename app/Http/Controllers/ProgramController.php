<?php
namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Barangay;
use App\Models\User;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProgramController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $barangay = Barangay::find($user->barangay_id);
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age;
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';
        
        return view('create-program', compact('user', 'barangay', 'age', 'roleBadge'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_time' => 'required',
                'category' => 'required|string|max:255',
                'location' => 'required|string',
                'description' => 'nullable|string',
                'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'published_by' => 'required|string|max:255',
                'registration_type' => 'required|in:create,link',
                'link_source' => 'nullable|url|required_if:registration_type,link',
                'registration_title' => 'nullable|string',
                'registration_description' => 'nullable|string|required_if:registration_type,create',
                'registration_open_date' => 'nullable|date|required_if:registration_type,create',
                'registration_open_time' => 'nullable|required_if:registration_type,create',
                'registration_close_date' => 'nullable|date|required_if:registration_type,create',
                'registration_close_time' => 'nullable|required_if:registration_type,create',
                'custom_fields' => 'nullable|string',
            ]);

            // Handle file upload
            if ($request->hasFile('display_image')) {
                $imagePath = $request->file('display_image')->store('programs', 'public');
                $validated['display_image'] = $imagePath;
            }

            // Convert time format - FIXED: Ensure proper time format
            $validated['event_time'] = $this->convertTimeTo24Hour($validated['event_time']);

            if ($validated['registration_type'] === 'create') {
                // FIXED: Proper time conversion for registration times
                if (!empty($validated['registration_open_time'])) {
                    $validated['registration_open_time'] = $this->convertTimeTo24Hour($validated['registration_open_time']);
                }
                if (!empty($validated['registration_close_time'])) {
                    $validated['registration_close_time'] = $this->convertTimeTo24Hour($validated['registration_close_time']);
                }

                // Store custom fields as JSON
                if ($request->has('custom_fields')) {
                    $validated['custom_fields'] = $request->custom_fields;
                }
            } else {
                // If registration type is link, set create registration fields to null
                $validated['registration_title'] = null;
                $validated['registration_description'] = null;
                $validated['registration_open_date'] = null;
                $validated['registration_open_time'] = null;
                $validated['registration_close_date'] = null;
                $validated['registration_close_time'] = null;
                $validated['custom_fields'] = null;
            }

            // Add user and barangay info
            $validated['user_id'] = Auth::id();
            $validated['barangay_id'] = Auth::user()->barangay_id;

            // Create the program
            $program = Program::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Program created successfully!',
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating program: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display program details for modal (JSON response)
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $programId = (int)$id;
            
            $program = Program::where('id', $programId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$program) {
                return response()->json(['error' => 'Program not found'], 404);
            }

            // Build response data safely
            $responseData = [
                'id' => $program->id,
                'title' => $program->title,
                'description' => $program->description,
                'event_date' => $program->event_date ? Carbon::parse($program->event_date)->format('Y-m-d') : null,
                'event_time' => $program->event_time,
                'location' => $program->location,
                'category' => $program->category,
                'published_by' => $program->published_by,
                'registration_type' => $program->registration_type,
                'link_source' => $program->link_source,
                'registration_title' => $program->registration_title,
                'registration_description' => $program->registration_description,
                'registration_open_date' => $program->registration_open_date,
                'registration_open_time' => $program->registration_open_time,
                'registration_close_date' => $program->registration_close_date,
                'registration_close_time' => $program->registration_close_time,
                'barangay_id' => $program->barangay_id,
                'custom_fields' => $program->custom_fields ? json_decode($program->custom_fields, true) : [],
            ];

            // Safely handle display_image URL
            if ($program->display_image) {
                try {
                    if (Storage::disk('public')->exists($program->display_image)) {
                        $responseData['display_image'] = asset('storage/' . $program->display_image);
                    } else {
                        $responseData['display_image'] = null;
                    }
                } catch (\Exception $e) {
                    $responseData['display_image'] = null;
                }
            } else {
                $responseData['display_image'] = null;
            }

            return response()->json($responseData);
            
        } catch (\Exception $e) {
            Log::error('Error fetching program: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store program registration - COMPLETELY REWRITTEN TO FIX DATETIME ISSUES
     */
   /**
 * Store program registration - FIXED DATETIME PARSING
 */
public function storeRegistration(Request $request): JsonResponse
{
    try {
        $user = Auth::user();
        
        Log::info('Starting registration process for user: ' . $user->id);
        
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'registration_data' => 'required|json',
        ]);

        Log::info('Validated data:', $validated);

        // Check if program exists and belongs to user's barangay
        $program = Program::where('id', $validated['program_id'])
                        ->where('barangay_id', $user->barangay_id)
                        ->first();

        if (!$program) {
            Log::warning('Program not found or not in barangay', [
                'program_id' => $validated['program_id'],
                'user_barangay' => $user->barangay_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Program not found or not available in your barangay.'
            ], 404);
        }

        Log::info('Program found: ' . $program->title);

        // Check if user already registered for this program
        $existingRegistration = ProgramRegistration::where('program_id', $program->id)
                                                ->where('user_id', $user->id)
                                                ->first();

        if ($existingRegistration) {
            Log::warning('User already registered', [
                'user_id' => $user->id,
                'program_id' => $program->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'You have already registered for this program.'
            ], 409);
        }

        // FIXED: Enhanced registration period checking with proper datetime handling
        if ($program->registration_type === 'create') {
            $now = Carbon::now();
            
            Log::info('Checking registration period for program: ' . $program->id);
            Log::info('Current time: ' . $now->toDateTimeString());

            // Check registration open time - FIXED PARSING
            if ($program->registration_open_date && $program->registration_open_time) {
                try {
                    // FIXED: Handle different time formats and ensure proper datetime creation
                    $openDate = Carbon::parse($program->registration_open_date);
                    $openTime = $this->ensureTimeFormat($program->registration_open_time);
                    
                    $registrationOpen = Carbon::create(
                        $openDate->year,
                        $openDate->month,
                        $openDate->day,
                        $openTime->hour,
                        $openTime->minute,
                        $openTime->second
                    );
                    
                    Log::info('Registration opens at: ' . $registrationOpen->toDateTimeString());
                    Log::info('Current time: ' . $now->toDateTimeString());
                    Log::info('Is registration open? ' . ($now->gte($registrationOpen) ? 'YES' : 'NO'));
                    
                    if ($now->lt($registrationOpen)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Registration has not opened yet. It opens on ' . $registrationOpen->format('M j, Y g:i A')
                        ], 400);
                    }
                } catch (\Exception $e) {
                    Log::error('Error parsing registration open datetime: ' . $e->getMessage());
                    Log::error('Open date: ' . $program->registration_open_date);
                    Log::error('Open time: ' . $program->registration_open_time);
                    // Continue with registration if there's an error parsing dates
                    Log::warning('Continuing registration despite date parsing error');
                }
            }

            // Check registration close time - FIXED PARSING
            if ($program->registration_close_date && $program->registration_close_time) {
                try {
                    // FIXED: Handle different time formats and ensure proper datetime creation
                    $closeDate = Carbon::parse($program->registration_close_date);
                    $closeTime = $this->ensureTimeFormat($program->registration_close_time);
                    
                    $registrationClose = Carbon::create(
                        $closeDate->year,
                        $closeDate->month,
                        $closeDate->day,
                        $closeTime->hour,
                        $closeTime->minute,
                        $closeTime->second
                    );
                    
                    Log::info('Registration closes at: ' . $registrationClose->toDateTimeString());
                    Log::info('Current time: ' . $now->toDateTimeString());
                    Log::info('Is registration closed? ' . ($now->gt($registrationClose) ? 'YES' : 'NO'));
                    
                    if ($now->gt($registrationClose)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Registration has closed. It closed on ' . $registrationClose->format('M j, Y g:i A')
                        ], 400);
                    }
                } catch (\Exception $e) {
                    Log::error('Error parsing registration close datetime: ' . $e->getMessage());
                    Log::error('Close date: ' . $program->registration_close_date);
                    Log::error('Close time: ' . $program->registration_close_time);
                    // Continue with registration if there's an error parsing dates
                    Log::warning('Continuing registration despite date parsing error');
                }
            }
        }

        // Generate reference ID
        $referenceId = 'PROG-' . strtoupper(uniqid());

        // Parse and enhance registration data
        $registrationData = json_decode($validated['registration_data'], true);
        
        Log::info('Original registration data:', $registrationData);

        // FIXED: Enhanced registration data structure
        $enhancedRegistrationData = [
            'user_profile' => [
                'full_name' => $user->given_name . ' ' . 
                              ($user->middle_name ? $user->middle_name . ' ' : '') . 
                              $user->last_name . 
                              ($user->suffix ? ' ' . $user->suffix : ''),
                'email' => $user->email,
                'contact_no' => $user->contact_no,
                'age' => $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : null,
                'barangay' => $user->barangay->name ?? 'N/A',
                'user_id' => $user->id
            ],
            'custom_fields' => $registrationData['custom_fields'] ?? [],
            'program_details' => [
                'program_id' => $program->id,
                'program_title' => $program->title,
                'program_category' => $program->category
            ],
            'submitted_at' => Carbon::now()->toDateTimeString(),
            'registration_id' => $referenceId
        ];

        Log::info('Enhanced registration data prepared');

        // Create registration with enhanced data
        $registration = ProgramRegistration::create([
            'program_id' => $program->id,
            'user_id' => $user->id,
            'reference_id' => $referenceId,
            'registration_data' => json_encode($enhancedRegistrationData),
            'status' => 'registered'
        ]);

        Log::info('Registration created successfully with ID: ' . $registration->id);

        return response()->json([
            'success' => true,
            'message' => 'Registration submitted successfully!',
            'reference_id' => $referenceId,
            'registration' => [
                'id' => $registration->id,
                'program_title' => $program->title,
                'submitted_at' => $registration->created_at->format('M j, Y g:i A')
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error submitting registration: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error submitting registration: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Helper method to ensure time is in proper format for Carbon
 */
private function ensureTimeFormat($timeString)
{
    try {
        // If time is already in proper format, parse it directly
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeString)) {
            return Carbon::createFromFormat('H:i:s', $timeString);
        }
        
        // If time is in 12-hour format with AM/PM
        if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i', $timeString, $matches)) {
            $hour = (int)$matches[1];
            $minute = $matches[2];
            $period = strtoupper($matches[3]);
            
            if ($period === 'PM' && $hour < 12) {
                $hour += 12;
            } elseif ($period === 'AM' && $hour == 12) {
                $hour = 0;
            }
            
            $timeFormatted = sprintf('%02d:%02d:00', $hour, $minute);
            return Carbon::createFromFormat('H:i:s', $timeFormatted);
        }
        
        // If just hours and minutes, add seconds
        if (preg_match('/^\d{1,2}:\d{2}$/', $timeString)) {
            return Carbon::createFromFormat('H:i:s', $timeString . ':00');
        }
        
        // Default: try to parse with Carbon's flexible parser
        return Carbon::parse($timeString);
        
    } catch (\Exception $e) {
        Log::error('Error ensuring time format: ' . $e->getMessage());
        Log::error('Time string: ' . $timeString);
        // Return current time as fallback
        return Carbon::now();
    }
}

    /**
     * Get user's program registrations
     */
    public function getUserRegistrations(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $registrations = ProgramRegistration::with('program')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registration) {
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'program_title' => $registration->program->title,
                        'status' => $registration->status,
                        'submitted_at' => $registration->created_at->format('M j, Y g:i A'),
                        'program_date' => $registration->program->event_date ? Carbon::parse($registration->program->event_date)->format('M j, Y') : 'TBA',
                        'registration_data' => $registrationData,
                        'user_profile' => $registrationData['user_profile'] ?? []
                    ];
                });

            return response()->json([
                'success' => true,
                'registrations' => $registrations
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations'
            ], 500);
        }
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        
        if ($program->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $barangay = Barangay::find($user->barangay_id);
        
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age;
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.edit', compact('program', 'user', 'barangay', 'age', 'roleBadge'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            
            if ($program->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_time' => 'required',
                'category' => 'required|string|max:255',
                'location' => 'required|string',
                'description' => 'nullable|string',
                'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'published_by' => 'required|string|max:255',
                'registration_type' => 'required|in:create,link',
                'link_source' => 'nullable|url|required_if:registration_type,link',
                'registration_title' => 'nullable|string',
                'registration_description' => 'nullable|string|required_if:registration_type,create',
                'registration_open_date' => 'nullable|date|required_if:registration_type,create',
                'registration_open_time' => 'nullable|required_if:registration_type,create',
                'registration_close_date' => 'nullable|date|required_if:registration_type,create',
                'registration_close_time' => 'nullable|required_if:registration_type,create',
                'custom_fields' => 'nullable|string',
            ]);

            // Handle file upload
            if ($request->hasFile('display_image')) {
                if ($program->display_image) {
                    Storage::disk('public')->delete($program->display_image);
                }
                
                $imagePath = $request->file('display_image')->store('programs', 'public');
                $validated['display_image'] = $imagePath;
            }

            // Convert time format
            $validated['event_time'] = $this->convertTimeTo24Hour($validated['event_time']);

            if ($validated['registration_type'] === 'create') {
                if (!empty($validated['registration_open_time'])) {
                    $validated['registration_open_time'] = $this->convertTimeTo24Hour($validated['registration_open_time']);
                }
                if (!empty($validated['registration_close_time'])) {
                    $validated['registration_close_time'] = $this->convertTimeTo24Hour($validated['registration_close_time']);
                }

                if ($request->has('custom_fields')) {
                    $validated['custom_fields'] = $request->custom_fields;
                }
            } else {
                $validated['registration_title'] = null;
                $validated['registration_description'] = null;
                $validated['registration_open_date'] = null;
                $validated['registration_open_time'] = null;
                $validated['registration_close_date'] = null;
                $validated['registration_close_time'] = null;
                $validated['custom_fields'] = null;
            }

            $program->update($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Program updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating program: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            
            if ($program->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            if ($program->display_image) {
                Storage::disk('public')->delete($program->display_image);
            }

            $program->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Program deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting program: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $user = Auth::user();
        $programs = Program::where('barangay_id', $user->barangay_id)
                          ->with('user')
                          ->latest()
                          ->get();

        $barangay = Barangay::find($user->barangay_id);
        
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age;
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.index', compact('programs', 'user', 'barangay', 'age', 'roleBadge'));
    }

    /**
     * Get program registrations for youth registration page - ENHANCED VERSION
     */
    public function getProgramRegistrations($programId): JsonResponse
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
                        'status' => $registration->status,
                        'registered_at' => $registration->created_at->format('M d, Y g:i A'),
                        'registration_data' => $registrationData,
                        'custom_fields' => $customFields,
                        'user_profile' => $userProfile
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
            Log::error('Error fetching program registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations: ' . $e->getMessage()
            ], 500);
        }
    }

   /**
 * FIXED: Improved time conversion method
 */
private function convertTimeTo24Hour($timeString)
{
    try {
        // If already in 24-hour format, return as is
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeString)) {
            return $timeString;
        }
        
        // If in 12-hour format with AM/PM, convert to 24-hour
        if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i', $timeString, $matches)) {
            $hour = (int)$matches[1];
            $minute = $matches[2];
            $period = strtoupper($matches[3]);
            
            if ($period === 'PM' && $hour < 12) {
                $hour += 12;
            } elseif ($period === 'AM' && $hour == 12) {
                $hour = 0;
            }
            
            return sprintf('%02d:%02d:00', $hour, $minute);
        }
        
        // If just hours and minutes, add seconds
        if (preg_match('/^\d{1,2}:\d{2}$/', $timeString)) {
            return $timeString . ':00';
        }
        
        // Default: try to parse with Carbon and return in 24-hour format
        $carbonTime = Carbon::parse($timeString);
        return $carbonTime->format('H:i:s');
        
    } catch (\Exception $e) {
        Log::error('Error converting time: ' . $e->getMessage());
        Log::error('Time string that failed: ' . $timeString);
        return '00:00:00'; // Fallback
    }
}
}