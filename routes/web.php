<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;


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


Route::get('/certificatepage', function () {
    return view('certificatepage');
})->name('certificatepage');


// Events page route
Route::get('/eventpage', function () {
    return view('eventpage'); 
})->name('eventpage');

// routes/web.php
Route::get('/faqspage', function () {
    return view('faqspage'); 
})->name('faqspage');

Route::get('/suggestionbox', function () {
    return view('suggestionbox'); 
})->name('suggestionbox');

Route::get('/attendance', function () {
    return view('attendancepage');
})->name('attendancepage');

// routes/web.php
Route::get('/serviceoffers', function () {
    return view('serviceoffers');
})->name('serviceoffers');

// routes/web.php
Route::get('/polls', function () {
    return view('pollspage');
})->name('polls.page');

Route::get('/evaluation', function () {
    return view('evaluationpage'); 
})->name('evaluation');


Route::get('/sk-dashboard', function () {
    return view('sk-dashboard'); 
})->name('sk.dashboard');


Route::get('/youth-profilepage', function () {
    return view('youth-profilepage'); 
})->name('youth-profilepage');

Route::get('/sk-eventpage', function () {
    return view('sk-eventpage'); 
})->name('sk-eventpage');

Route::get('/create-event', function () {
    return view('create-event'); 
})->name('create-event');

Route::get('/create-program', function () {
    return view('create-program'); 
})->name('create-program');

Route::get('/edit-event', function () {
    return view('edit-event'); 
})->name('edit-event');

Route::get('/certificate-request', function () {
    return view('certificate-request'); 
})->name('certificate-request');

Route::get('/certificate-request-list', function () {
    return view('certificate-request-list'); 
})->name('certificate-request-list');

Route::get('/youth-participation', function () {
    return view('youth-participation'); 
})->name('youth-participation');

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

Route::get('/sk-polls', function () {
    return view('sk-polls');
})->name('sk-polls');

Route::get('/edit-program', function () {
    return view('edit-program');
})->name('edit-program');

Route::get('/youth-assistance', function () {
    return view('youth-assistance');
})->name('youth-assistance');

Route::get('/youth-suggestion', function () {
    return view('youth-suggestion');
})->name('youth-suggestion');

Route::get('/youth-program-registration', function () {
    return view('youth-program-registration');
})->name('youth-program-registration');

Route::get('/youth-registration-list', function () {
    return view('youth-registration-list');
})->name('youth-registration-list');

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





//CONTROLLER ROUTES

// Remove the duplicate route definition and use the controller
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/profile', [ProfileController::class, 'index'])->name('profilepage');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


Route::get('/get-provinces/{region_id}', [LocationController::class, 'getprovinces']);
Route::get('/get-cities/{province_id}', [LocationController::class, 'getCities']);
Route::get('/get-barangays/{city_id}', [LocationController::class, 'getBarangays']);

Route::get('/kk/create-account/{id}', [KkMemberController::class, 'createAccount'])->name('kk.createAccount');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Admin dashboard and actions
Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::patch('/admin/users/{id}/approve', [AdminController::class, 'approve'])->name('admin.users.approve');
Route::patch('/admin/users/{id}/reject', [AdminController::class, 'reject'])->name('admin.users.reject');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {

// Profile routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profilepage');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/check-session', [ProfileController::class, 'checkSession'])->name('profile.checkSession');
Route::get('/profile/user-data', [ProfileController::class, 'getUserData'])->name('profile.userData');
});

Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
// Avatar routes
Route::post('/profile/avatar/update', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
Route::post('/profile/avatar/remove', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
Route::get('/profile/data', [ProfileController::class, 'getProfileData'])->name('profile.data');
