<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::view('/', 'welcome');

// Routes d'authentification
Auth::routes();
Route::get('/home', HomeController::class.'@index')->name('home');

// Routes qui ne nécessitent que l'authentification
Route::middleware('auth')->group(function () {
    // Routes pour la gestion des écoles
    Route::resource('schools', SchoolController::class);
    Route::post('schools/{school}/switch', [SchoolController::class, 'switchSchool'])->name('schools.switch');
    
    // Routes pour la gestion des administrateurs d'école
    Route::controller(SchoolAdminController::class)->prefix('schools/{school}/admins')->name('schools.admins.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{admin}/edit', 'edit')->name('edit');
        Route::put('/{admin}', 'update')->name('update');
        Route::delete('/{admin}', 'destroy')->name('destroy');
    });
});

// Routes qui nécessitent une école sélectionnée
Route::middleware(['auth', 'school'])->group(function () {
    // Dashboard routes
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/statistics', 'getStatistics')->name('dashboard.statistics');
    });

    // Student remaining amount
    Route::get('/payments/student-remaining/{student}', [PaymentController::class, 'getStudentRemainingAmount'])
        ->name('payments.student-remaining');
    
    // Resource routes
    Route::resources([
        'campuses' => CampusController::class,
        'fields' => FieldController::class,
        'students' => StudentController::class,
        'payments' => PaymentController::class,
    ]);
    
    // Payment receipt
    Route::get('/payments/{payment}/print', [PaymentController::class, 'printReceipt'])
        ->name('payments.print');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        // Payment reports
        Route::get('/payments', [PaymentController::class, 'report'])
            ->name('payments');

        // Student reports
        Route::get('/students', [StudentController::class, 'report'])
            ->name('students');

        // Generate PDF reports
        Route::get('/payments/export', [PaymentController::class, 'exportPdf'])
            ->name('payments.pdf');
        Route::get('/students/export', [StudentController::class, 'exportPdf'])
            ->name('students.pdf');
    });
});
