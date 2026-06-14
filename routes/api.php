<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\DashboardApiController;

Route::get('/dashboard', [DashboardApiController::class, 'index']);
Route::get('/students', [StudentApiController::class, 'index']);
Route::get('/students/{id}', [StudentApiController::class, 'show']);
Route::get('/payments', [PaymentApiController::class, 'index']);
Route::get('/payments/{id}', [PaymentApiController::class, 'show']);
