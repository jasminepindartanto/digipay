<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']); 

Route::resource('students', StudentController::class);
Route::resource('payments', PaymentController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/payments/reminder', [PaymentController::class, 'reminder'])->name('payments.reminder');
Route::get('/laporan/export', [DashboardController::class, 'export'])->name('laporan.export');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');