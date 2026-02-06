<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\LoanReturnController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\LoanApprovalController;
use App\Http\Controllers\Petugas\ReportController;
use App\Http\Controllers\Petugas\ReturnMonitoringController;
use App\Http\Controllers\Peminjam\LoanRequestController;
use App\Http\Controllers\Peminjam\ReturnRequestController;
use App\Http\Controllers\Peminjam\ToolCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('tools', ToolController::class)->except(['show']);
    Route::resource('loans', LoanController::class);
    Route::resource('returns', LoanReturnController::class)->except(['show']);
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::view('/dashboard', 'petugas.dashboard')->name('dashboard');

    Route::get('approvals', [LoanApprovalController::class, 'index'])->name('approvals.index');
    Route::post('approvals/{loan}/approve', [LoanApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{loan}/reject', [LoanApprovalController::class, 'reject'])->name('approvals.reject');

    Route::get('returns', [ReturnMonitoringController::class, 'index'])->name('returns.index');
    Route::post('returns/{return}/receive', [ReturnMonitoringController::class, 'receive'])->name('returns.receive');
    Route::post('returns/{return}/reject', [ReturnMonitoringController::class, 'reject'])->name('returns.reject');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');
});

Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::view('/dashboard', 'peminjam.dashboard')->name('dashboard');

    Route::get('tools', [ToolCatalogController::class, 'index'])->name('tools.index');

    Route::get('loans', [LoanRequestController::class, 'index'])->name('loans.index');
    Route::get('loans/create', [LoanRequestController::class, 'create'])->name('loans.create');
    Route::post('loans', [LoanRequestController::class, 'store'])->name('loans.store');

    Route::post('loans/{loan}/return-request', [ReturnRequestController::class, 'store'])->name('returns.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
