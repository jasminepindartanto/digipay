<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('dashboard');
});
Route::resource('students', StudentController::class);
Route::resource('payments', PaymentController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');