<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SystemConfigController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
 * SPAMPK - Sistem Pelaporan Automatik Migrasi Pasca-Kuantum
 */

Route::redirect('/', '/log-masuk');

// --- Tetamu (belum log masuk) ---
Route::middleware('guest')->group(function () {
    Route::get('/log-masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/log-masuk', [AuthController::class, 'login']);
});

// --- Pengguna disahkan ---
Route::middleware(['auth', 'role'])->group(function () {
    Route::post('/log-keluar', [AuthController::class, 'logout'])->name('logout');

    // Papan Pemuka — semua peranan
    Route::get('/papan-pemuka', [DashboardController::class, 'index'])->name('dashboard');

    // Profil pengguna — semua peranan
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // Laporan — Pengurusan, Pegawai Penyelaras Analisis, Pegawai Analisis
    Route::middleware('role:pengurusan,penyelaras,analisis')->group(function () {
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/cipta', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/laporan/{report}/sunting', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/laporan/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::post('/laporan/{report}/hantar', [ReportController::class, 'submit'])->name('reports.submit');
        Route::post('/laporan/{report}/semak', [ReportController::class, 'review'])->name('reports.review');
        Route::get('/laporan/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
        Route::delete('/laporan/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    });

    // Muat Naik Fail & Penyerahan — Pegawai Penyelaras Analisis, Pegawai Analisis
    Route::middleware('role:penyelaras,analisis')->group(function () {
        Route::get('/muat-naik', [FileUploadController::class, 'index'])->name('uploads.index');
        Route::get('/penyerahan', [SubmissionController::class, 'index'])->name('submissions.index');
    });

    // Pentadbir Sistem sahaja
    Route::middleware('role:pentadbir')->group(function () {
        Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/cipta', [UserController::class, 'create'])->name('users.create');
        Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');
        Route::get('/pengguna/{user}/sunting', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/log-audit', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/konfigurasi-sistem', [SystemConfigController::class, 'index'])->name('config.index');
    });
});