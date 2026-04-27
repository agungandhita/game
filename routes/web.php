<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

// ─── Root redirect ─────────────────────────────────────────────────────────
Route::get('/', function () {
    return auth()->check() ? redirect()->route('quiz.index') : redirect()->route('login');
});

// ─── Auth routes (guest only) ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Quiz (Siswa) ──────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/',                              [QuizController::class, 'index'])->name('index');
    Route::get('/grade/{grade}',                 [QuizController::class, 'levels'])->name('levels');
    Route::post('/level/{level}/start',          [QuizController::class, 'start'])->name('start');
    Route::get('/session/{session}/play',        [QuizController::class, 'play'])->name('play');
    Route::post('/session/{session}/answer',     [QuizController::class, 'answer'])->name('answer');
    Route::post('/session/{session}/finish',     [QuizController::class, 'finish'])->name('finish');
    Route::get('/result/{result}',               [QuizController::class, 'result'])->name('result');
});

// ─── Admin ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('users/{user}/scores', [\App\Http\Controllers\Admin\UserController::class, 'scores'])->name('users.scores');

    // Bank Soal
    Route::get('grades',                     [\App\Http\Controllers\Admin\GradeController::class, 'index'])->name('grades.index');
    Route::get('grades/{grade}',             [\App\Http\Controllers\Admin\GradeController::class, 'show'])->name('grades.show');
    Route::resource('levels',                \App\Http\Controllers\Admin\LevelController::class)->only(['index', 'edit', 'update']);
    Route::resource('questions',             \App\Http\Controllers\Admin\QuestionController::class);

    // API helper: load levels by grade (untuk Alpine.js AJAX)
    Route::get('levels-by-grade/{grade}', function (\App\Models\Quiz\Grade $grade) {
        return $grade->levels()->orderBy('order')->get(['id', 'name']);
    })->name('levels.by-grade');

    // Laporan Skor
    Route::get('scores',         [\App\Http\Controllers\Admin\ScoreController::class, 'index'])->name('scores.index');
    Route::get('scores/export',  [\App\Http\Controllers\Admin\ScoreController::class, 'export'])->name('scores.export');
});
