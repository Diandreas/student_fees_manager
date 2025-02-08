<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics'])->name('dashboard.statistics');


    // Campus management
    Route::resource('campuses', CampusController::class);

    // Field management
    Route::resource('fields', FieldController::class);

    // Student management
    Route::resource('students', StudentController::class);

    // Payment management
    Route::resource('payments', PaymentController::class);
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
