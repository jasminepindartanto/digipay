<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LearningSessionController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\StudentPackageController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\AlumniController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('students', StudentController::class);
    Route::post('/students/{student}/renew',[StudentController::class,'renew'])
        ->name('students.renew');
    Route::get('/students/{student}/renew', [StudentController::class, 'renewForm'])
        ->name('students.renew.form');
    Route::get('/students/{student}/summary', [StudentController::class, 'summary'])
        ->name('students.summary');
    Route::get('/student-packages/{package}',[StudentPackageController::class,'show'])
        ->name('packages.show');
    #Route::get('/students/{id}/progress', [StudentController::class, 'progress'])
        #->name('students.progress');
        #Route::put(
            #'/students/{id}/progress',[StudentController::class, 'updateProgress']
        #)->name('students.progress.update');
    // Learning Sessions
// Semua role yang sudah login dapat melihat sesi

Route::get('/learning-sessions', [LearningSessionController::class, 'index'])
    ->name('learning-sessions.index');


// =====================================================
// TUTOR ONLY
// Harus diletakkan SEBELUM {learningSession}
// =====================================================

Route::middleware('role:tutor')->group(function () {

    Route::get('/learning-sessions/create', [LearningSessionController::class, 'create'])
        ->name('learning-sessions.create');

    Route::post('/learning-sessions', [LearningSessionController::class, 'store'])
        ->name('learning-sessions.store');

    Route::get('/learning-sessions/{learningSession}/edit', [LearningSessionController::class, 'edit'])
        ->name('learning-sessions.edit');

    Route::put('/learning-sessions/{learningSession}', [LearningSessionController::class, 'update'])
        ->name('learning-sessions.update');

    Route::delete('/learning-sessions/{learningSession}', [LearningSessionController::class, 'destroy'])
        ->name('learning-sessions.destroy');

});


// =====================================================
// ADMIN & TUTOR - LIHAT DETAIL
// Diletakkan PALING BAWAH karena memakai {parameter}
// =====================================================

Route::get('/learning-sessions/{learningSession}', [LearningSessionController::class, 'show'])
    ->name('learning-sessions.show');
    Route::get('/register', [RegistrationController::class, 'create'])
        ->name('register.form');
    Route::post('/register', [RegistrationController::class, 'store'])
        ->name('register.store');
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
    Route::get('/prediction',[PredictionController::class,'index'])
        ->name('prediction.index');
    Route::post('/reminders/{forecast}/send',[ReminderController::class, 'send'])
        ->name('reminders.send');
    Route::get('/students/{student}/package-history',[StudentController::class, 'packageHistory'])
    ->name('students.package-history');
});

 
   # Route::resource('users', UserController::class);

#Route::middleware(['auth', 'role:owner,admin'])->group(function () {
    #Route::get('/students', [StudentController::class, 'index']);
    #Route::get('/payments', [PaymentController::class, 'index']);

Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('alumni', AlumniController::class)
        ->parameters(['alumni' => 'student',])
    ->only(['index', 'show']);
    Route::patch('/students/{student}/graduate',[StudentController::class, 'graduate'])
        ->name('students.graduate');
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/student/{student}/bill',[PaymentController::class, 'getStudentBill'])
        ->name('payments.student.bill');
    Route::get('/payments/reminder', [PaymentController::class, 'reminder'])
        ->name('payments.reminder');
    #Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    #Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/receipt',[PaymentController::class, 'receipt'])
        ->name('payments.receipt');
    Route::get('/laporan/export', [DashboardController::class, 'export'])->name('laporan.export');
    #Route::resource('students', StudentController::class);
    Route::get('/registrations', [RegistrationController::class, 'index'])
        ->name('registrations.index');
    Route::get('/registrations/create',[RegistrationController::class, 'createAdmin'])
        ->name('registrations.create');
    Route::post('/registrations',[RegistrationController::class, 'storeAdmin'])
        ->name('registrations.storeAdmin');
    Route::get('/registrations/{id}', [RegistrationController::class, 'show'])
    ->name('registrations.show');
    Route::get('/registrations/{id}/edit', [RegistrationController::class, 'edit'])
        ->name('registrations.edit');
    Route::put('/registrations/{id}', [RegistrationController::class, 'update'])
        ->name('registrations.update');
    Route::post('/registrations/{id}/approve', [RegistrationController::class, 'approve'])
        ->name('registrations.approve');
    Route::get('/student-packages/{student}/renew',[StudentPackageController::class, 'renew'])
        ->name('student-packages.renew');
    Route::post('/student-packages/{student}/renew',[StudentPackageController::class, 'storeRenew'])
        ->name('student-packages.storeRenew');
    Route::patch('/students/{student}/deactivate',[StudentController::class, 'deactivate'])
        ->name('students.deactivate');
    Route::post('/payments/generate-monthly-bills',
    [PaymentController::class, 'generateMonthlyBills'])
        ->name('payments.generateMonthlyBills');
    Route::post('/users/{id}/role', [UserController::class, 'updateRole'])
    ->name('users.updateRole');
    Route::get('/payments/export/excel', [PaymentController::class, 'exportExcel'])
        ->name('payments.export.excel');
    Route::get('/payments/export', [PaymentController::class, 'export'])
        ->name('payments.export');
    Route::get('/payments/export/pdf', [PaymentController::class, 'exportPdf'])
        ->name('payments.export.pdf');
    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])
        ->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export.pdf'); 
    Route::get('/students/export/excel',[StudentController::class,'exportExcel'])
        ->name('students.export.excel');
    Route::get('/students/export/pdf',[StudentController::class,'exportPdf'])
        ->name('students.export.pdf');  
});
