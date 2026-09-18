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

// ── Protected Routes ────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Customers API - with 'api.' prefix to avoid name conflicts
    Route::apiResource('customers', CustomerController::class)
         ->names([
             'index'   => 'api.customers.index',
             'store'   => 'api.customers.store',
             'show'    => 'api.customers.show',
             'update'  => 'api.customers.update',
             'destroy' => 'api.customers.destroy',
         ]);

    // Products API
    Route::get('/products',           [ProductController::class, 'index'])->name('api.products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show');

    // Invoices API
    Route::get('/invoices',           [InvoiceController::class, 'index'])->name('api.invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('api.invoices.show');

    // Leads API
    Route::get('/leads',       [LeadController::class, 'index'])->name('api.leads.index');
    Route::get('/leads/{lead}',[LeadController::class, 'show'])->name('api.leads.show');

    // Payments API
    Route::get('/payments', [PaymentController::class, 'index'])->name('api.payments.index');

});