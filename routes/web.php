<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage');
});

Route::get('loginpage', function () {
    return view('loginpage');
})->name('loginpage');

// routes/web.php
Route::get('/register', function () {
    return view('register'); // resources/views/register.blade.php
});
