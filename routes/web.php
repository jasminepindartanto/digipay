<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;

Route::get('/', [DashboardController::class, 'index']); 

Route::resource('students', StudentController::class);
Route::resource('payments', PaymentController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/payments/reminder', [PaymentController::class, 'reminder'])->name('payments.reminder');
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/{id}/receipt', [PaymentController::class, 'receipt'])
    ->name('payments.receipt');
Route::get('/laporan/export', [DashboardController::class, 'export'])->name('laporan.export');
Route::resource('students', StudentController::class);

Route::get('/register', [RegistrationController::class, 'create'])
    ->name('register.form');
Route::post('/register', [RegistrationController::class, 'store'])
    ->name('register.store');
Route::get('/registrations', [RegistrationController::class, 'index'])
    ->name('registrations.index');
Route::post('/registrations/{id}/approve', [RegistrationController::class, 'approve'])
    ->name('registrations.approve');
Route::post('/registrations/{id}/reject', [RegistrationController::class, 'reject'])
    ->name('registrations.reject');

Route::post('/logout', function () {
    //Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');