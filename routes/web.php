<?php

//use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SKDashboardController;
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
use App\Http\Controllers\ProgramController; 
use App\Http\Controllers\YouthProgramRegistrationController; 
use App\Http\Controllers\YouthAssistanceController; 

Route::get('/', function () {
    return view('landingpage');
});

Route::get('loginpage', function () {
    return view('loginpage');
})->name('loginpage');

Route::get('/registration/success', function () {
    return view('registration-success'); 
})->name('registration.success');


Route::get('/profilepage', function () {
    return view('profilepage');
});

// UPDATED: Certificate page route
Route::get('/certificatepage', [EvaluationController::class, 'certificatePage'])->name('certificatepage');

// ========== EVENT ROUTES ==========
Route::get('/events', [EventController::class, 'index'])->name('sk-eventpage');
Route::get('/events/create', [EventController::class, 'create'])->name('create-event');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/launch', [EventController::class, 'launchEvent']);
Route::post('/events/{id}/generate-passcode', [EventController::class, 'generatePasscode']);
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
Route::get('/eventpage', [EventController::class, 'userEvents'])->name('eventpage');


Route::get('/faqspage', function () {
    return view('faqspage'); 
})->name('faqspage');



Route::get('/suggestionbox', [SuggestionController::class, 'index'])->name('suggestionbox');
Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

Route::view('/attendance', 'attendancepage')->name('attendancepage');
Route::get('/serviceoffers', [ServicesController::class, 'index'])->name('serviceoffers');

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

Route::get('/evaluation', [EvaluationController::class, 'index'])->name('evaluation');
Route::post('/evaluation', [EvaluationController::class, 'store']);
Route::get('/evaluation/check/{eventId}', [EvaluationController::class, 'checkEvaluation']);

// NEW: Certificate routes
Route::get('/evaluation/certificates', [EvaluationController::class, 'getCertificates'])->name('evaluation.certificates');
Route::post('/evaluation/request-print', [EvaluationController::class, 'requestPrint'])->name('evaluation.request-print');

// FIXED: SK Dashboard route - use controller instead of direct view
Route::get('/sk-dashboard', [SKDashboardController::class, 'index'])->name('sk.dashboard');

// FIXED: Youth Profile route - use controller instead of direct view
Route::get('/youth-profilepage', [YouthProfileController::class, 'index'])->name('youth-profilepage');

// ========== PROGRAM ROUTES ========== ADD THESE ROUTES
Route::get('/create-program', [ProgramController::class, 'create'])->name('create-program');
Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{id}', [ProgramController::class, 'show'])->name('programs.show');
Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');

Route::get('/edit-event', function () {
    return view('edit-event'); 
})->name('edit-event');

Route::get('/certificate-request', function () {
    return view('certificate-request'); 
})->name('certificate-request');

Route::get('/certificate-request-list', function () {
    return view('certificate-request-list'); 
})->name('certificate-request-list');

Route::get('/youth-participation', [YouthParticipationController::class, 'index'])
    ->name('youth-participation')
    ->middleware('auth');

Route::get('/list-of-attendees', function () {
    return view('list-of-attendees'); 
})->name('attendees.index');

Route::get('/youth-status', function () {
    return view('youth-statuspage');
})->name('youth-statuspage');

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

Route::get('/sk-services-offer', function () {
    return view('sk-services-offer');
})->name('sk-services-offer');

Route::get('/reports', function () {
    return view('reports');
})->name('reports');

Route::get('/view-youth-profile', function () {
    return view('view-youth-profile');
})->name('view-youth-profile');

// CONTROLLER ROUTES

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/preview', [RegisterController::class, 'preview'])->name('register.preview');
Route::get('/register/captcha', [RegisterController::class, 'showCaptcha'])->name('register.captcha');
Route::post('/register/complete', [RegisterController::class, 'complete'])->name('register.complete');

// ========== LOCATION ROUTES ==========
Route::get('/get-provinces/{region_id}', [LocationController::class, 'getprovinces']);
Route::get('/get-cities/{province_id}', [LocationController::class, 'getCities']);
Route::get('/get-barangays/{city_id}', [LocationController::class, 'getBarangays']);

// ========== AUTH ROUTES ==========
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// UPDATED: Login route with rate limiting
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ADMIN ROUTES ==========

// ✅ Protected admin dashboard
Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard')
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


// ========== PROTECTED ROUTES ==========
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profilepage');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/check-session', [ProfileController::class, 'checkSession'])->name('profile.checkSession');
    Route::get('/profile/user-data', [ProfileController::class, 'getUserData'])->name('profile.userData');
    
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
});

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
        Route::post('/reset', 'resetPassword')->name('reset'); // Renamed method to resetPassword for clarity
});


Route::post('/feedback/submit', [FeedbackController::class, 'store'])->name('feedback.submit');

Route::post('/programs', [ProgramController::class, 'store'])
    ->name('programs.store');
// Program registration routes
Route::post('/program-registrations', [ProgramController::class, 'storeRegistration'])->name('programs.store-registration');
Route::get('/my-program-registrations', [ProgramController::class, 'getUserRegistrations'])->name('programs.my-registrations');
// Add this route for fetching program registrations
Route::get('/youth-program-registration/{programId}/registrations', [YouthProgramRegistrationController::class, 'getProgramRegistrations'])
    ->name('youth-program-registration.registrations')
    ->middleware('auth');
    
Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('edit-event');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');