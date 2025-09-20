<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('landingpage');
});

Route::get('loginpage', function () {
    return view('loginpage');
})->name('loginpage');

// Remove the duplicate route definition and use the controller
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/registration/success', function () {
    return view('registration-success'); 
})->name('registration.success');


Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/profilepage', function () {
    return view('profilepage');
});

Route::get('/profile', [ProfileController::class, 'index'])->name('profilepage');



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


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

