<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OptionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    Route::post('/agent/register', [AuthController::class, 'registerAgent'])->name('agent.register');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::put('/customers/{id}', [CustomerController::class, 'updateInfos'])->name('customers.update');
    Route::put('/customers/{id}/driver', [CustomerController::class, 'updateLicense'])->name('customers.update-license');
    Route::put('/customers/{id}/billing', [CustomerController::class, 'updateBillingAddress'])->name('customers.update-billing-addr');
});

Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::post('/customers/{id}/driver', [CustomerController::class, 'addLicense'])->name('customers.add-license');
Route::post('/customers/{id}/billing', [CustomerController::class, 'addBillingAddress'])->name('customers.add-billing-addr');
Route::post('/customers/{id}/auth', [AuthController::class, 'registerCustomer'])->name('customers.register');

Route::get('/options', [OptionController::class, 'index'])->name('options.index');
