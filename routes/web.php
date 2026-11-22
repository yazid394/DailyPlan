<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
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

    // Profil & preferensi tidur
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/preferensi', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');

    // Kalender
    Route::get('/kalender', [CalendarController::class, 'index'])->name('calender.index');

    // API internal untuk ambil data saat bulan digeser
    Route::get('/kalender/data', [CalendarController::class, 'fetchMonthData'])->name('calender.data');

    // Tugas
    Route::get('/tugas', [TaskController::class, 'index'])->name('task.index');
    Route::get('/tugas/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/tugas', [TaskController::class, 'store'])->name('task.store');
    Route::get('/tugas/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/tugas/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tugas/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

    // Aksi realtime dari halaman Pantau Tugas
    Route::patch('/tugas/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('task.toggle');
    Route::patch('/subtugas/{subtask}/toggle', [TaskController::class, 'toggleSubtask'])->name('subtask.toggle');

    // Kemajuan
    Route::get('/kemajuan', [UserController::class, 'progress'])->name('progress.index');
    Route::get('/kemajuan/export-pdf', [UserController::class, 'exportProgressPdf'])->name('progress.pdf');
});
