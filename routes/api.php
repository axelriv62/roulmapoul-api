<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum')->name('me');
Route::post('/agent/register', [AuthController::class, 'registerAgent'])->middleware('auth:sanctum')->name('agent.register');
