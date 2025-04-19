<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    Route::post('/agent/register', [AuthController::class, 'registerAgent'])->name('agent.register');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
});

Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::post('/customers/{id}/driver', [CustomerController::class, 'addLicense'])->name('customers.add-license');
Route::post('/customers/{id}/billing', [CustomerController::class, 'addBillingAddress'])->name('customers.add-billing-addr');
