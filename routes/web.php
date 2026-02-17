<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ApiDocController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\Auth\LoginController;

// Redirect root to login or dashboard
Route::get('/', function () {
    return redirect()->route('login');
});

// Simple Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('leads', LeadController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('opportunities', OpportunityController::class);
    Route::resource('budgets', BudgetController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/api-docs', [ApiDocController::class, 'index'])->name('api-docs.index');
    Route::resource('users', UserController::class);
    Route::get('/login-logs', [LoginLogController::class, 'index'])->name('login-logs.index');
});
