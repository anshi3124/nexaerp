<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ActivityController;

// Auth Routes
Auth::routes(['register' => false]);

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRM - Customers
    Route::resource('customers', CustomerController::class);

    // CRM - Leads
    Route::resource('leads', LeadController::class);

    // Activities
    Route::resource('activities', ActivityController::class)->only(['store', 'destroy']);

});