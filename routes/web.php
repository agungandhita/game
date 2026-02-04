<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/world/{slug}', [DashboardController::class, 'world'])->name('world.show');

    Route::get('/level/{level}', [GameController::class, 'show'])->name('level.show');
    Route::post('/level/{level}/submit', [GameController::class, 'submit'])->name('level.submit');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Manajemen Nilai
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{id}', [GradeController::class, 'show'])->name('grades.show');
    Route::get('/grades/export/csv', [GradeController::class, 'export'])->name('grades.export');

    // CRUD Soal
    Route::resource('questions', QuestionController::class);

    // Manajemen User
    Route::resource('users', UserController::class);

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
});
