<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('register.store');

    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/kalender', function () {
        // Ini akan memanggil file calender.blade.php di folder views lu
        return view('calender');
    })->name('calender');

    Route::get('/tugas', function () {
        // Ini akan memanggil file task.blade.php
        return view('task');
    })->name('task');

    Route::get('/kemajuan', function () {
        // Ini akan memanggil file progress.blade.php
        return view('progress');
    })->name('progress');
});
