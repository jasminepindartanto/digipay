<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\DashboardApiController;


Route::get('/dashboard', [DashboardApiController::class, 'index']);
Route::get('/students', [StudentApiController::class, 'index']);
Route::get('/students/{id}', [StudentApiController::class, 'show']);
Route::get('/payments', [PaymentApiController::class, 'index']);
Route::get('/payments/{id}', [PaymentApiController::class, 'show']);

Route::get('/', [DashboardController::class, 'index']); 
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']); 
    Route::get('/dashboard',[DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('students', StudentController::class);
    Route::resource('payments', PaymentController::class)
        ->except(['show']);
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
    Route::post('/payments/generate-monthly-bills',
        [PaymentController::class, 'generateMonthlyBills'])
        ->name('payments.generateMonthlyBills');
    Route::get('/payments/export/excel', [PaymentController::class, 'exportExcel'])
        ->name('payments.export.excel');
    Route::get('/payments/export', [PaymentController::class, 'export'])
        ->name('payments.export');
    Route::get('/payments/export/pdf',[PaymentController::class, 'exportPdf'])
        ->name('payments.export.pdf');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');});
