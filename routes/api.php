<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/agent/register', [AuthController::class, 'registerAgent'])->name('agent.register');
});

// Customer routes
Route::post('/customers/{id}/driver', [CustomerController::class, 'addLicense'])->name('customers.add-license');
Route::post('/customers/{id}/billing', [CustomerController::class, 'addBillingAddress'])->name('customers.add-billing-addr');
Route::post('/customers/{id}/auth', [AuthController::class, 'registerCustomer'])->name('customers.register');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');



Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/customer/{id}/driver', [CustomerController::class, 'updateLicense'])->name('customers.update-license');
    Route::put('/customer/{id}/billing', [CustomerController::class, 'updateBillingAddress'])->name('customers.update-billing-addr');
    Route::get('/customer/{id}/', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customer/{id}', [CustomerController::class, 'updateInfos'])->name('customers.update');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
});

// Rental routes
Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('/rentals/cancel/{id}', [RentalController::class, 'destroy'])->name('rentals.delete');
    Route::get('/rentals/car/{id}', [RentalController::class, 'indexOfCar'])->name('rentals.index-car');
    Route::get('/rentals/agency/{id}', [RentalController::class, 'indexOfAgency'])->name('rentals.index-agency');
    Route::get('/rentals/customer/{id}', [RentalController::class, 'indexOfCustomer'])->name('rentals.index-customer');
    Route::put('/rentals/{id}', [RentalController::class, 'update'])->name('rentals.update');
    Route::get('/rentals/{id}', [RentalController::class, 'show'])->name('rentals.show');
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
});

// Car routes
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/agency/{id}', [CarController::class, 'indexAgency'])->name('cars.index-agency');

Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/options', [OptionController::class, 'index'])->name('options.index');
Route::get('/warranties', [WarrantyController::class, 'index'])->name('warranties.index');
