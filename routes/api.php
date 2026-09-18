<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\PaymentController;

// ── Public Routes ───────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ── Protected Routes (requires Sanctum token) ───────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Customers
    Route::apiResource('customers', CustomerController::class);

    // Products
    Route::get('/products',      [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Invoices
    Route::get('/invoices',           [InvoiceController::class, 'index']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);

    // Leads
    Route::get('/leads',       [LeadController::class, 'index']);
    Route::get('/leads/{lead}',[LeadController::class, 'show']);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);

});