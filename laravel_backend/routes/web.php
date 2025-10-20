<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminAuthController;

// Public Routes (No Auth Required)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

// ML Test Routes (Public)
Route::prefix('ml')->name('ml.')->group(function () {
    Route::get('/status', [App\Http\Controllers\MLController::class, 'status'])->name('status');
    Route::post('/predict', [App\Http\Controllers\MLController::class, 'predict'])->name('predict');
    Route::get('/categories', [App\Http\Controllers\MLController::class, 'categories'])->name('categories');
});

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/admin', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/analytics', [App\Http\Controllers\DashboardController::class, 'analytics'])->name('analytics');

    // Reports Management Routes
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminReportController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminReportController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AdminReportController::class, 'store'])->name('store');
        Route::get('/{report}', [App\Http\Controllers\AdminReportController::class, 'show'])->name('show');
        Route::get('/{report}/edit', [App\Http\Controllers\AdminReportController::class, 'edit'])->name('edit');
        Route::put('/{report}', [App\Http\Controllers\AdminReportController::class, 'update'])->name('update');
        Route::delete('/{report}', [App\Http\Controllers\AdminReportController::class, 'destroy'])->name('destroy');
        Route::patch('/{report}/status', [App\Http\Controllers\AdminReportController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-delete', [App\Http\Controllers\AdminReportController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export/csv', [App\Http\Controllers\AdminReportController::class, 'export'])->name('export');
    });

    // Users Management Routes
    Route::prefix('admin/users')->name('admin.users.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminUserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}', [App\Http\Controllers\AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\AdminUserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [App\Http\Controllers\AdminUserController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-action', [App\Http\Controllers\AdminUserController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [App\Http\Controllers\AdminUserController::class, 'export'])->name('export');
        Route::get('/stats/data', [App\Http\Controllers\AdminUserController::class, 'getStats'])->name('stats');
    });

    // API Test Page
    Route::get('/admin/test', function () {
        return view('test');
    })->name('api-test');

    // ML Test Page
    Route::get('/admin/ml-test', function () {
        return view('ml-test');
    })->name('ml-test');
});
