<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TranscriptController;
use App\Http\Middleware\SimulationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SIAKAD Enkripsi (Phase 2 — With Backend)
|--------------------------------------------------------------------------
*/

// ── Auth Routes ─────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Root Redirect ────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Protected Routes (requires auth.mahasiswa middleware) ────────────────
Route::middleware('auth.mahasiswa')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript');
    Route::post('/transcript/verify-pin', [TranscriptController::class, 'verifyPin'])->name('transcript.verify-pin');
    Route::get('/transcript/download', [TranscriptController::class, 'download'])->name('transcript.download');
});

// ── Admin Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth.mahasiswa', 'auth.admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('mahasiswas', \App\Http\Controllers\Admin\MahasiswaController::class);
    Route::resource('mata-kuliahs', \App\Http\Controllers\Admin\MataKuliahController::class);
    Route::resource('jadwal-kuliahs', \App\Http\Controllers\Admin\JadwalKuliahController::class);
    Route::resource('nilais', \App\Http\Controllers\Admin\NilaiController::class);
});

// ── Simulation Routes (prefix /simulasi) ──────────────────────────────
// Menduplikasi semua route di bawah /simulasi, menggunakan controller yang sama,
// namun dibungkus oleh SimulationMiddleware yang menonaktifkan enkripsi.
Route::prefix('simulasi')->name('simulasi.')->middleware(SimulationMiddleware::class)->group(function (): void {

    // ── Auth Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Root redirect
    Route::get('/', fn () => redirect()->route('simulasi.login'));

    // ── Protected Routes (mahasiswa)
    Route::middleware('auth.mahasiswa')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
        Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript');
        Route::post('/transcript/verify-pin', [TranscriptController::class, 'verifyPin'])->name('transcript.verify-pin');
        Route::get('/transcript/download', [TranscriptController::class, 'download'])->name('transcript.download');
    });

    // ── Admin Routes
    Route::middleware(['auth.mahasiswa', 'auth.admin'])->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('mahasiswas', \App\Http\Controllers\Admin\MahasiswaController::class);
        Route::resource('mata-kuliahs', \App\Http\Controllers\Admin\MataKuliahController::class);
        Route::resource('jadwal-kuliahs', \App\Http\Controllers\Admin\JadwalKuliahController::class);
        Route::resource('nilais', \App\Http\Controllers\Admin\NilaiController::class);
    });
});
