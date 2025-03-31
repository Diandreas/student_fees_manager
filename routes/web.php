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
use App\Http\Controllers\Auth\SchoolRegisterController;
use App\Http\Controllers\SchoolSettingsController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ActivityLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::view('/', 'welcome');

// Routes d'authentification
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Routes d'inscription des écoles
Route::get('/register/school', [SchoolRegisterController::class, 'showRegistrationForm'])->name('school.register');
Route::post('/register/school', [SchoolRegisterController::class, 'register']);

// Routes protégées par l'authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Gestion des écoles
    Route::get('/schools/select', [SchoolController::class, 'select'])->name('schools.select');
    Route::post('/schools/{school}/switch', [SchoolController::class, 'switchSchool'])->name('schools.switch');
    
    // Routes protégées par la sélection d'une école
    Route::middleware(['school'])->group(function () {
        // Routes pour les paramètres des écoles
        Route::get('/schools/{school}/settings', [SchoolSettingsController::class, 'index'])->name('schools.settings');
        Route::put('/schools/{school}/settings', [SchoolSettingsController::class, 'updateGeneral'])->name('schools.settings.update');
        Route::put('/schools/{school}/settings/general', [SchoolSettingsController::class, 'updateGeneral'])->name('schools.settings.general');
        Route::put('/schools/{school}/settings/contact', [SchoolSettingsController::class, 'updateContact'])->name('schools.settings.contact');
        Route::put('/schools/{school}/settings/billing', [SchoolSettingsController::class, 'updateBilling'])->name('schools.settings.billing');
        Route::put('/schools/{school}/settings/logo', [SchoolSettingsController::class, 'updateLogo'])->name('schools.settings.logo');
        Route::put('/schools/{school}/settings/status', [SchoolSettingsController::class, 'updateStatus'])->name('schools.settings.status');
        Route::put('/schools/{school}/settings/header', [SchoolSettingsController::class, 'updateDocumentHeader'])->name('schools.settings.header');
        
        // Routes pour la gestion des administrateurs d'école
        Route::prefix('schools/{school}/admins')->name('schools.admins.')->controller(SchoolAdminController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{admin}/edit', 'edit')->name('edit');
            Route::put('/{admin}', 'update')->name('update');
            Route::delete('/{admin}', 'destroy')->name('destroy');
        });
        
        // Gestion des entités d'établissement
        Route::resource('schools', SchoolController::class)->except(['show']);
        Route::get('/schools/{school}', [SchoolController::class, 'show'])->name('schools.show');
        
        // Routes pour les campus
        Route::resource('campuses', CampusController::class);
        
        // Routes pour les niveaux d'éducation
        Route::resource('education-levels', EducationLevelController::class);
        
        // Routes pour les filières avec route d'exportation
        Route::get('/fields/{field}/report', [FieldController::class, 'report'])->name('fields.report');
        Route::resource('fields', FieldController::class);
        
        // Routes pour les étudiants avec route d'exportation
        Route::get('/students/{student}/report', [StudentController::class, 'report'])->name('students.report');
        Route::get('/students/print', [StudentController::class, 'printList'])->name('students.print');
        Route::resource('students', StudentController::class);
        
        // Routes pour la gestion des paiements
        Route::get('/payments/student-remaining/{student}', [PaymentController::class, 'getStudentRemainingAmount'])
            ->name('payments.student-remaining');
        
        // API pour obtenir les informations complètes de paiement d'un étudiant
        Route::get('/payments/student-info/{student}', [PaymentController::class, 'getStudentPaymentInfoApi'])
            ->name('payments.student-info');
        
        // Exportation et impression des paiements
        Route::get('/payments/print-list', [PaymentController::class, 'printList'])->name('payments.print-list');
        
        // Payment receipt
        Route::get('/payments/{payment}/print', [PaymentController::class, 'printReceipt'])
            ->name('payments.print');
        
        // Routes de paiement
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::get('/payments/export/{student_id?}', [PaymentController::class, 'exportExcel'])->name('payments.export');
        Route::get('/payments/export-excel/{student_id?}', [PaymentController::class, 'exportExcel'])->name('payments.export-excel');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        
        // Rapports et tableaux de bord
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/students', [ReportController::class, 'students'])->name('reports.students');
            Route::get('/payments', [ReportController::class, 'payments'])->name('reports.payments');
            Route::get('/finances', [ReportController::class, 'finances'])->name('reports.finances');
            Route::get('/performance', [ReportController::class, 'performance'])->name('reports.performance');
            
            // Exportations
            Route::get('/export/students', [ReportController::class, 'exportStudents'])->name('reports.export.students');
            Route::get('/export/payments', [ReportController::class, 'exportPayments'])->name('reports.export.payments');
            Route::get('/export/finances', [ReportController::class, 'exportFinances'])->name('reports.export.finances');
        });

        // Routes pour le profil utilisateur
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::put('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');

        // Gestion des utilisateurs
        Route::resource('users', UserController::class)->except(['show']);

        // Gestion des factures
        Route::controller(InvoiceController::class)->prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{invoice}', 'show')->name('show');
            Route::get('/{invoice}/edit', 'edit')->name('edit');
            Route::put('/{invoice}', 'update')->name('update');
            Route::delete('/{invoice}', 'destroy')->name('destroy');
            Route::get('/{invoice}/print', 'print')->name('print');
            Route::get('/{invoice}/send', 'send')->name('send');
        });

        // Routes pour le journal des activités
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show', 'destroy']);
    });
});
