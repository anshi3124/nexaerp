<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockTransactionController;

Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRM
    Route::resource('customers', CustomerController::class);
    Route::resource('leads', LeadController::class);
    Route::resource('activities', ActivityController::class)->only(['store', 'destroy']);

    // Inventory
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::get('/stock-transactions', [StockTransactionController::class, 'index'])->name('stock-transactions.index');
    Route::post('/stock-transactions', [StockTransactionController::class, 'store'])->name('stock-transactions.store');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'store', 'destroy']);

    // AJAX
    Route::get('/api/products/{product}/price', [ProductController::class, 'getPrice'])->name('products.price');

});