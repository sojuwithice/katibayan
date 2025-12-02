<?php

//use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SKDashboardController;
use App\Http\Controllers\SKAnalyticsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PollsController;
use App\Http\Controllers\SKPollsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\SKEvaluationController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\YouthProfileController;
use App\Http\Controllers\YouthParticipationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\EvaluationRespondentsController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\UserLoginController;
use App\Models\Admin;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CertificateRequestController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ServiceOffersController;
use App\Http\Controllers\YouthProgramRegistrationController;
use App\Http\Controllers\YouthAssistanceController;
use App\Http\Controllers\SystemFeedbackController;
use App\Http\Controllers\SKController;
use App\Http\Controllers\SkRoleRequestController;
use App\Models\User;
use App\Models\Event;   
use App\Models\Program;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('landingpage');
});

Route::get('loginpage', function () {
    return view('loginpage');
})->name('loginpage');

Route::get('/registration/success', function () {
    return view('registration-success'); 
})->name('registration.success');

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/profilepage', function () {
    return view('profilepage');
});

Route::get('/sk-role-view', function () {
    return view('/sk-role-view');
});

// UPDATED: Certificate page route
Route::get('/certificatepage', [EvaluationController::class, 'certificatePage'])->name('certificatepage');

// ========== EVENT ROUTES ==========
Route::get('/events', [EventController::class, 'index'])->name('sk-eventpage');
Route::get('/events/create', [EventController::class, 'create'])->name('create-event');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show'); // ADDED NAME
Route::post('/events/{id}/launch', [EventController::class, 'launchEvent']);
Route::post('/events/{id}/generate-passcode', [EventController::class, 'generatePasscode']);
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
Route::get('/eventpage', [EventController::class, 'userEvents'])->name('eventpage');

// routes/web.php
Route::get('/faqspage', function () {
    return view('faqspage'); 
})->name('faqspage');
Route::get('/sk-analytics', [SKAnalyticsController::class, 'index'])->name('sk.analytics');

// FIXED: Suggestion Box route - use controller instead of direct view
Route::get('/suggestionbox', [SuggestionController::class, 'index'])->name('suggestionbox');

Route::view('/attendance', 'attendancepage')->name('attendancepage');

Route::get('/service-offers', [ServiceOffersController::class, 'index'])->name('serviceoffers');

// ========== POLLS ROUTES ==========
Route::get('/polls', [PollsController::class, 'index'])->name('polls.page');
Route::post('/polls', [PollsController::class, 'store'])->name('polls.store');
Route::post('/polls/{pollId}/vote', [PollsController::class, 'vote'])->name('polls.vote');
Route::get('/polls/{pollId}/results', [PollsController::class, 'getPollResults'])->name('polls.results');

// SK Polls Routes
Route::get('/sk-polls', [SKPollsController::class, 'index'])->name('sk-polls');
Route::post('/sk-polls', [SKPollsController::class, 'store'])->name('sk-polls.store');
Route::get('/sk-polls/{pollId}/respondents', [SKPollsController::class, 'getRespondents'])->name('sk-polls.respondents');
Route::delete('/sk-polls/{pollId}', [SKPollsController::class, 'destroy'])->name('sk-polls.destroy');

// ========== EVALUATION ROUTES ==========
Route::get('/evaluation', [EvaluationController::class, 'index'])->name('evaluation');
Route::post('/evaluation', [EvaluationController::class, 'store']);

// INILAGAY SA ITAAS: Unahin ang mga specific routes
Route::get('/evaluation/certificates', [EvaluationController::class, 'getCertificates'])->name('evaluation.certificates');
Route::get('/evaluation/check', [EvaluationController::class, 'checkEvaluation'])->name('evaluation.check');
Route::post('/evaluation/request-print', [EvaluationController::class, 'requestPrint'])->name('evaluation.request-print');

// INILAGAY SA BABA: Ihuli ang mga may wildcard
Route::get('/evaluation/check/{eventId}', [EvaluationController::class, 'checkEvaluation']);
Route::get('/evaluation/{id}', [EvaluationController::class, 'show'])->name('evaluation.show');

// FIXED: SK Dashboard route - use controller instead of direct view
Route::get('/sk-dashboard', [SKDashboardController::class, 'index'])->name('sk.dashboard');

// FIXED: Youth Profile route - use controller instead of direct view
Route::get('/youth-profilepage', [YouthProfileController::class, 'index'])->name('youth-profilepage');

// ========== PROGRAM ROUTES ==========
Route::get('/create-program', [ProgramController::class, 'create'])->name('create-program');
Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{id}', [ProgramController::class, 'show'])->name('programs.show');
Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');

// Program registration routes
Route::post('/program-registrations', [ProgramController::class, 'storeRegistration'])->name('programs.store-registration');
Route::get('/my-program-registrations', [ProgramController::class, 'getUserRegistrations'])->name('programs.my-registrations');
Route::get('/programs/{programId}/registrations', [ProgramController::class, 'getProgramRegistrations'])->name('programs.registrations');

Route::get('/edit-event', function () {
    return view('edit-event'); 
})->name('edit-event');

Route::get('/youth-participation', [YouthParticipationController::class, 'index'])
    ->name('youth-participation')
    ->middleware('auth');

Route::get('/list-of-attendees', function () {
    return view('list-of-attendees'); 
})->name('attendees.index');

Route::get('/youth-status', function () {
    return view('youth-statuspage');
})->name('youth-statuspage');


Route::get('/registration-success', function () {
    return view('registration-success');
})->name('registration.success');

Route::get('/sk-evaluation-feedback', function () {
    return view('sk-evaluation-feedback');
})->name('sk-evaluation-feedback');

Route::get('/sk-eval-review', function () {
    return view('sk-eval-review');
})->name('sk-eval-review');

// CONTROLLER ROUTES

Route::get('/edit-program', function () {
    return view('edit-program');
})->name('edit-program');

Route::get('/youth-assistance', [YouthAssistanceController::class, 'index'])->name('youth-assistance');
Route::post('/youth-assistance/filter', [YouthAssistanceController::class, 'filter'])->name('youth-assistance.filter');

Route::get('/youth-suggestion', [SuggestionController::class, 'youthSuggestion'])->name('youth-suggestion');

// UPDATED: Youth Program Registration Routes - Remove the closure and use controller
Route::get('/youth-program-registration', [YouthProgramRegistrationController::class, 'index'])
    ->name('youth-program-registration');

// NEW: Youth Registration List Route
Route::get('/youth-registration-list/{programId}', [YouthProgramRegistrationController::class, 'showRegistrationList'])
    ->name('youth-registration-list');

Route::get('/list-of-eval-respondents', function () {
    return view('list-of-eval-respondents');
})->name('list-of-eval-respondents');

Route::get('/reports', function () {
    return view('reports');
})->name('reports');

Route::get('/view-youth-profile', function () {
    return view('view-youth-profile');
})->name('view-youth-profile');

// ========== REGISTRATION ROUTES ==========
// FIXED: Add the missing registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/preview', [RegisterController::class, 'preview'])->name('register.preview'); // ADD THIS LINE
Route::get('/register/captcha', [RegisterController::class, 'showCaptcha'])->name('register.captcha'); // ADD THIS LINE
Route::post('/register/complete', [RegisterController::class, 'complete'])->name('register.complete'); // ADD THIS LINE

// CONTROLLER ROUTES

Route::get('/profile', [ProfileController::class, 'index'])->name('profilepage');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/get-provinces/{region_id}', [LocationController::class, 'getprovinces']);
Route::get('/get-cities/{province_id}', [LocationController::class, 'getCities']);
Route::get('/get-barangays/{city_id}', [LocationController::class, 'getBarangays']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// UPDATED: Login route with rate limiting
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ADMIN ROUTES ==========

// ✅ Protected admin dashboard
Route::get('/admindashb', [AdminController::class, 'dashboard'])
    ->name('admindashb')
    ->middleware('auth:admin');

// ✅ FIXED: Admin analytics route with correct method
Route::get('/admin/analytics', [AdminController::class, 'analytics'])
    ->name('admin-analytics')
    ->middleware('auth:admin');

Route::get('/user-management', [AdminController::class, 'userManagement']) 
    ->name('user-management')
    ->middleware('auth:admin'); 

// User approval and rejection routes
Route::patch('/admin/users/{id}/approve', [AdminController::class, 'approve'])->name('admin.users.approve')->middleware('auth:admin');
Route::patch('/admin/users/{id}/reject', [AdminController::class, 'reject'])->name('admin.users.reject')->middleware('auth:admin');

// Admin login and logout routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::get('/admin/user-management', [AdminController::class, 'userManagement'])
    ->name('user-management2')
    ->middleware('auth:admin');

// UPDATED: Users Feedback route - use controller instead of direct view
Route::get('/users-feedback', [SystemFeedbackController::class, 'index'])->name('users-feedback')->middleware('auth:admin');

Route::get('/admin/settings', function () {
    return view('admin-settings'); // You'll need to create this view
})->name('admin-settings')->middleware('auth:admin');

// ========== SUGGESTION ROUTES ==========
// FIXED: Add the suggestion store route
Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profilepage');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/check-session', [ProfileController::class, 'checkSession'])->name('profile.checkSession');
    Route::get('/profile/user-data', [ProfileController::class, 'getUserData'])->name('profile.userData');

    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    // Avatar routes
    Route::post('/profile/avatar/update', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/avatar/remove', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::get('/profile/data', [ProfileController::class, 'getProfileData'])->name('profile.data');

    // Attendance routes
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/my-attendances', [AttendanceController::class, 'getUserAttendances'])->name('attendance.my');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // SK Dashboard
    Route::get('/sk-dashboard', [SKDashboardController::class, 'index'])->name('sk.dashboard');
    
    // SK Evaluation Feedback - FIXED: Using controller instead of direct view
    Route::get('/sk-evaluation-feedback', [SKEvaluationController::class, 'index'])->name('sk-evaluation-feedback');
    
    // SK Evaluation Review
    Route::get('/sk/evaluation/review/{event_id}', [SKEvaluationController::class, 'showReview'])->name('sk-eval-review');
    
    // Password routes
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    
    // Avatar routes
    Route::post('/profile/avatar/update', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/avatar/remove', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::get('/profile/data', [ProfileController::class, 'getProfileData'])->name('profile.data');
    
    // Attendance routes
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/my-attendances', [AttendanceController::class, 'getUserAttendances'])->name('attendance.my');
    
    // SK Suggestions Route
    Route::get('/sk-suggestions', [SuggestionController::class, 'getSKSuggestions'])->name('sk.suggestions');
    
    // Suggestion Box Route - ADDED INSIDE AUTH MIDDLEWARE
    Route::get('/suggestionbox', [SuggestionController::class, 'index'])->name('suggestionbox');
    
    // Protected Poll Routes (for voting)
    Route::post('/polls/{pollId}/vote', [PollsController::class, 'vote'])->name('polls.vote');
    Route::get('/polls/{pollId}/results', [PollsController::class, 'getPollResults'])->name('polls.results');
    
    // Protected SK Poll Routes (for creating/managing polls)
    Route::post('/sk-polls', [SKPollsController::class, 'store'])->name('sk-polls.store');
    Route::get('/sk-polls/{pollId}/respondents', [SKPollsController::class, 'getRespondents'])->name('sk-polls.respondents');
    Route::delete('/sk-polls/{pollId}', [SKPollsController::class, 'destroy'])->name('sk-polls.destroy');
    
    // Protected Program Routes ADD THESE INSIDE AUTH MIDDLEWARE
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{id}', [ProgramController::class, 'show'])->name('programs.show');
    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');
    
    // Protected Program Registration Routes
    Route::post('/program-registrations', [ProgramController::class, 'storeRegistration'])->name('programs.store-registration');
    Route::get('/my-program-registrations', [ProgramController::class, 'getUserRegistrations'])->name('programs.my-registrations');
    Route::get('/programs/{programId}/registrations', [ProgramController::class, 'getProgramRegistrations'])->name('programs.registrations');
    
    // Protected Youth Registration List Route
    Route::get('/youth-registration-list/{programId}', [YouthProgramRegistrationController::class, 'showRegistrationList'])
        ->name('youth-registration-list');
        
    // ========== ATTENDANCE MANAGEMENT ROUTES ==========
    // Update attendance status for program registrations
    Route::post('/program-registration/{registrationId}/attendance', [YouthProgramRegistrationController::class, 'updateAttendance'])
        ->name('program-registration.update-attendance');
        
    // Get attendance statistics for a program
    Route::get('/program/{programId}/attendance-stats', [YouthProgramRegistrationController::class, 'getAttendanceStats'])
        ->name('program.attendance-stats');
        
    // ========== DAILY ATTENDANCE ROUTES ==========
    // Get daily attendance data for a specific registration - ADDED THIS ROUTE
    Route::get('/youth-program-registration/daily-attendance/{registrationId}', [YouthProgramRegistrationController::class, 'getDailyAttendance'])
        ->name('youth-program-registration.daily-attendance');
        
    // ========== EVALUATION ROUTES (PROTECTED) ==========
    Route::post('/evaluation', [EvaluationController::class, 'store']);
    Route::get('/evaluation/check', [EvaluationController::class, 'checkEvaluation'])->name('evaluation.check');
    Route::get('/evaluation/{id}', [EvaluationController::class, 'show'])->name('evaluation.show');

    // ========== SERVICE OFFERS ROUTES (PROTECTED) ==========
    
    Route::post('/services', [ServiceOffersController::class, 'storeService'])->name('services.store');
    Route::put('/services/{id}', [ServiceOffersController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{id}', [ServiceOffersController::class, 'deleteService'])->name('services.delete');
    Route::get('/services/{id}/details', [ServiceOffersController::class, 'getServiceDetails'])->name('services.details');
    Route::post('/organizational-chart', [ServiceOffersController::class, 'storeOrganizationalChart'])->name('organizational-chart.store');

    // ========== SYSTEM FEEDBACK ROUTES (PROTECTED) ==========
    Route::post('/feedback/submit', [SystemFeedbackController::class, 'store'])->name('feedback.submit'); // UPDATED
});

// ========== SERVICE OFFERS ROUTES ==========
// MOVED OUTSIDE AUTH MIDDLEWARE - This should be accessible to authenticated users
Route::get('/sk-services-offer', [ServiceOffersController::class, 'index'])->name('sk-services-offer')->middleware('auth');
Route::get('/service-offers', [ServiceOffersController::class, 'serviceoffers'])->name('serviceoffers')->middleware('auth');

Route::post('/polls/{pollId}/reset-vote', [PollsController::class, 'resetVote'])->name('polls.reset-vote');

Route::get('/events/{id}/qr', [EventController::class, 'showQr'])->name('events.qr');
Route::get('/attendance/records', [AttendanceController::class, 'getAllAttendances']);
Route::get('/attendance/my', [AttendanceController::class, 'myAttendance'])->name('attendance.my');

Route::get('/attendance/records', [AttendanceController::class, 'getAllAttendances'])->name('attendance.records');

Route::get('/attendance/records', [AttendanceController::class, 'getEventAttendances'])
    ->name('attendance.records');

// List of Attendees Route
Route::get('/list-of-attendees', [AttendanceController::class, 'showAttendees'])->name('attendees.index');

// OTP Routes
Route::post('/auth/google/token', [GoogleController::class, 'getEmailFromToken']);
Route::post('/send-otp', [VerificationController::class, 'sendOtp']);
Route::post('/verify-otp', [VerificationController::class, 'verifyOtp']);

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::get('/evaluation/respondents/{event_id}', [EvaluationRespondentsController::class, 'showRespondents'])
    ->name('evaluation.respondents');

Route::controller(ForgotPasswordController::class)
    ->prefix('forgot-password')
    ->as('forgot-password.')
    ->group(function () {
        Route::post('/send-otp', 'sendOtp')->name('send-otp');
        Route::post('/verify-otp', 'verifyOtp')->name('verify-otp');
        Route::post('/reset', 'resetPassword')->name('reset');
});

// ✅ Certificate Request Routes
Route::get('/certificate-request', [CertificateRequestController::class, 'index'])->name('certificate-request');
Route::post('/certificate-request', [CertificateRequestController::class, 'store'])->name('certificate.request');

// --- ITO 'YUNG TAMANG FIX ---
// Pinalitan natin 'yung URL at 'yung pangalan (name) para tumugma sa view
Route::get('/certificate-request/{type}/{id}', [CertificateRequestController::class, 'showList'])->name('certificate.showList');


// 'Yung mga route sa baba ay para sa CertificateController, kaya okay lang iwan 'yan
Route::get('/certificate-request-list/{event_id}', [CertificateController::class, 'showCertificateRequests'])->name('certificate-request-list');
Route::post('/accept-requests', [CertificateController::class, 'acceptRequests'])->name('certificate.accept');
Route::post('/set-schedule', [CertificateController::class, 'setSchedule'])->name('certificate.setSchedule');

// Sa web.php

Route::get('/evaluation', [EvaluationController::class, 'index'])->name('evaluation');

Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])
       ->name('notifications.markAsRead')
       ->middleware('auth');

Route::post('/certificate/mark-as-claimed', [CertificateController::class, 'confirmClaimed'])
    ->middleware('auth') // Ensure only logged-in users can access
    ->name('certificates.confirmClaimed');

    Route::get('/certificate-requests/{event_id}/status', [CertificateController::class, 'getStatuses'])
    ->middleware(['auth', 'role:sk']) // Example middleware
    ->name('certificate.getStatuses');

   Route::post('/certificate/mark-as-claimed', [CertificateController::class, 'confirmClaimed'])
     ->name('certificate.markAsClaimed');

     Route::post('/certificate/claim', [CertificateController::class, 'claimCertificate']);


     Route::get('/certificate-request-list/{event_id}', [CertificateController::class, 'showCertificateRequests'])
    ->name('certificate-request-list');

   Route::post('/certificate/claim', [App\Http\Controllers\CertificateController::class, 'claimCertificate'])
    ->name('certificate.claim');


//programs

Route::post('/programs', [ProgramController::class, 'store'])
    ->name('programs.store');
    
Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('edit-event');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
// Notifications
Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.list');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

// ========== DAILY ATTENDANCE ROUTES ==========
Route::post('/programs/update-daily-attendance', [YouthProgramRegistrationController::class, 'updateDailyAttendance'])
    ->name('programs.update-daily-attendance');




Route::post('/assistance/update', [SKController::class, 'updateAssistanceInfo'])->name('sk.assistance.update');
Route::post('/sk/assistance/save', [SKDashboardController::class, 'saveAssistanceInfo'])
    ->name('sk.save.assistance');



Route::middleware(['auth'])->group(function () {


    // PART 1: Request Access
    Route::post('/sk/request-access', [SkRoleRequestController::class, 'store'])
           ->name('sk.request.access');

    // PART 2: SK Chair Requests
    Route::get('/sk/requests', [SkRoleRequestController::class, 'index'])
           ->name('sk.requests.index');
           
    Route::post('/sk/requests/{id}/approve', [SkRoleRequestController::class, 'approve'])
           ->name('sk.requests.approve');
           
    Route::post('/sk/requests/{id}/reject', [SkRoleRequestController::class, 'reject'])
           ->name('sk.requests.reject');

    Route::post('/sk/set-role', [SkRoleRequestController::class, 'setRole'])
       ->name('sk.set.role');

    Route::get('/sk/role-dashboard', function () {
        $currentUser = Auth::user(); 

        // 1. Chairperson Logic (Existing)
        $chairperson = App\Models\User::where('barangay_id', $currentUser->barangay_id)
                           ->where('role', 'sk')
                           ->first();

        // 2. Members Logic (Existing)
        $members = App\Models\User::where('barangay_id', $currentUser->barangay_id)
                       ->whereNotNull('sk_role') 
                       ->where('sk_role', '!=', '') 
                       ->where('id', '!=', $chairperson ? $chairperson->id : 0) 
                       ->orderBy('sk_role', 'asc') 
                       ->get();

        // === 3. NEW: GET ACCOMPLISHED PROJECTS (Grouped by Year) ===
        
        // A. Kunin ang PAST EVENTS (Filtered by Barangay)
        $pastEvents = App\Models\Event::where('barangay_id', $currentUser->barangay_id)
                        ->where('created_at', '<', now()) // Gamit ang created_at (sure ball column)
                        ->orderBy('created_at', 'desc') 
                        ->get()
                        ->map(function ($item) {
                            return (object)[
                                'title' => $item->title,
                                'type'  => 'Event', 
                                'date'  => $item->created_at 
                            ];
                        });

        // B. Kunin ang PAST PROGRAMS (Kung meron)
        $pastPrograms = collect([]); 
        if (class_exists('App\Models\Program')) {
             $pastPrograms = App\Models\Program::where('barangay_id', $currentUser->barangay_id)
                        ->where('created_at', '<', now()) 
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function ($item) {
                            return (object)[
                                'title' => $item->title,
                                'type'  => 'Program',
                                'date'  => $item->created_at
                            ];
                        });
        }

        // C. Pagsamahin, I-sort, at I-GROUP BY YEAR
        $completedProjects = $pastEvents->concat($pastPrograms)
            ->sortByDesc('date')
            ->groupBy(function($item) {
                // Kukunin ang Year mula sa date (e.g. "2025")
                return \Carbon\Carbon::parse($item->date)->format('Y');
            });

        return view('sk-role-view', compact('currentUser', 'chairperson', 'members', 'completedProjects')); 
    })->name('sk.role.view');

    Route::post('/sk/update-committees', [SkRoleRequestController::class, 'updateCommittees'])
        ->name('sk.committees.update');

});

Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread_count');
Route::get('/sk/committee', [SKController::class, 'showSKCommittee'])->name('sk.committee.view');

// Magdagdag ng route para sa fetch
Route::get('/sk/officials-list', [SKDashboardController::class, 'getSkOfficials'])->name('sk.officials.list');

Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::post('/reports/folder', [ReportController::class, 'storeFolder'])->name('reports.folder.store');
    Route::post('/reports/upload', [ReportController::class, 'uploadFile'])->name('reports.upload');
    Route::delete('/reports/{type}/{id}', [ReportController::class, 'destroy'])->name('reports.delete');
    Route::get('/reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');
});

// Idagdag ito sa loob ng iyong auth middleware group, kasama ng ibang reports routes
Route::get('/reports/view/{id}', [App\Http\Controllers\ReportController::class, 'viewFile'])->name('reports.view');
Route::post('/reports/backup/{id}', [App\Http\Controllers\ReportController::class, 'createBackup'])->name('reports.backup');
Route::post('/reports/archive/{id}', [App\Http\Controllers\ReportController::class, 'archiveFile'])->name('reports.archive');
Route::post('/reports/rename/{type}/{id}', [App\Http\Controllers\ReportController::class, 'renameItem'])->name('reports.rename');
Route::post('/submit-report', [ReportController::class, 'submitReport'])->name('reports.submit');

Route::post('/sk/assistance-info', [ServiceOffersController::class, 'updateAssistanceInfo'])->name('sk.assistance.update');

// web.php (I-verify at i-apply ang dalawang ito)

// 1. DELETE Route (Gagamit ng POST + _method: DELETE)
Route::post('/sk/organizational-chart/{id}/delete', [ServiceOffersController::class, 'deleteOrganizationalChart']) // ADDED '/delete' suffix
    ->name('sk.organizational-chart.delete');
    
// 2. UPDATE Route (Gagamit ng POST + _method: PUT)
Route::post('/sk/organizational-chart/{id}/update', [ServiceOffersController::class, 'updateOrganizationalChart']) // ADDED '/update' suffix
    ->name('sk.organizational-chart.update');

    // ========== ADMIN SYSTEM FEEDBACK ROUTES ==========
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/system-feedbacks', [SystemFeedbackController::class, 'index'])->name('admin.system-feedbacks.index');
    Route::put('/admin/system-feedbacks/{systemFeedback}/status', [SystemFeedbackController::class, 'updateStatus'])->name('admin.system-feedbacks.updateStatus');
    Route::get('/admin/system-feedbacks/stats', [SystemFeedbackController::class, 'getStats'])->name('admin.system-feedbacks.stats');
});