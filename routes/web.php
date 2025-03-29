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
use App\Http\Controllers\SettingsController;

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

// Routes qui nécessitent que l'authentification
Route::middleware('auth')->group(function () {
    // Route de sélection d'école - explicitement définie
    Route::get('/select-school', [SchoolController::class, 'select'])->name('schools.select');
    
    // Routes pour la gestion des écoles
    Route::resource('schools', SchoolController::class);
    Route::post('schools/{school}/switch', [SchoolController::class, 'switchSchool'])->name('schools.switch');
    Route::get('schools/{school}/settings', [SchoolSettingsController::class, 'index'])->name('schools.settings');
    Route::put('schools/{school}/settings', [SchoolSettingsController::class, 'updateGeneral'])->name('schools.settings.update');
    
    // Routes pour les paramètres de l'école
    Route::get('/school/settings', [SchoolSettingsController::class, 'index'])->name('school.settings');
    Route::put('/school/{school}/settings/general', [SchoolSettingsController::class, 'updateGeneral'])->name('school.settings.general');
    Route::put('/school/{school}/settings/terminology', [SchoolSettingsController::class, 'updateTerminology'])->name('school.settings.terminology');
    Route::post('/school/{school}/education-levels', [SchoolSettingsController::class, 'storeEducationLevel'])->name('school.education-levels.store');
    Route::put('/school/{school}/education-levels/{level}', [SchoolSettingsController::class, 'updateEducationLevel'])->name('school.education-levels.update');
    Route::delete('/school/{school}/education-levels/{level}', [SchoolSettingsController::class, 'destroyEducationLevel'])->name('school.education-levels.destroy');
    
    // Routes pour la gestion des widgets
    Route::get('/school/settings/widgets', [SchoolSettingsController::class, 'manageWidgets'])->name('school.settings.widgets');
    Route::post('/school/widgets', [SchoolSettingsController::class, 'storeWidget'])->name('school.widgets.store');
    Route::put('/school/widgets', [SchoolSettingsController::class, 'updateWidgets'])->name('school.widgets.update');
    Route::delete('/school/widgets/{widget}', [SchoolSettingsController::class, 'deleteWidget'])->name('school.widgets.destroy');
    
    // Routes pour la gestion des rapports
    Route::get('/school/settings/reports', [SchoolSettingsController::class, 'reportsIndex'])->name('school.settings.reports.index');
    Route::put('/school/{school}/settings/reports', [SchoolSettingsController::class, 'updateReports'])->name('school.settings.reports.update');
    Route::get('/school/settings/reports/preview', [SchoolSettingsController::class, 'previewReport'])->name('school.settings.reports.preview');
    
    // Routes pour la gestion de l'apparence
    Route::get('/school/settings/appearance', [SchoolSettingsController::class, 'appearanceIndex'])->name('school.settings.appearance');
    Route::put('/school/{school}/settings/appearance', [SchoolSettingsController::class, 'updateAppearance'])->name('school.settings.appearance.update');
    
    // Routes pour la gestion de la terminologie
    Route::get('/school/settings/terminology', [SchoolSettingsController::class, 'terminologyIndex'])->name('school.settings.terminology');
    Route::put('/school/{school}/settings/terminology', [SchoolSettingsController::class, 'updateTerminology'])->name('school.settings.terminology.update');
    
    // Routes pour la gestion des notifications
    Route::get('/school/settings/notifications', [SchoolSettingsController::class, 'notificationsIndex'])->name('school.settings.notifications');
    Route::put('/school/{school}/settings/notifications', [SchoolSettingsController::class, 'updateNotifications'])->name('school.settings.notifications.update');
    
    // Routes pour la gestion des documents
    Route::get('/school/settings/documents', [SchoolSettingsController::class, 'documentsIndex'])->name('school.settings.documents');
    Route::post('/school/{school}/settings/documents', [SchoolSettingsController::class, 'storeDocument'])->name('school.settings.documents.store');
    Route::delete('/school/{school}/settings/documents/{document}', [SchoolSettingsController::class, 'destroyDocument'])->name('school.settings.documents.destroy');
    
    // Routes pour la gestion des administrateurs d'école
    Route::controller(SchoolAdminController::class)->prefix('schools/{school}/admins')->name('schools.admins.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{admin}/edit', 'edit')->name('edit');
        Route::put('/{admin}', 'update')->name('update');
        Route::delete('/{admin}', 'destroy')->name('destroy');
    });
});

// Routes protégées par le middleware auth et school
Route::middleware(['auth', 'school'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les campus
    Route::resource('campuses', CampusController::class);
    
    // Routes pour les niveaux d'éducation
    Route::resource('education-levels', EducationLevelController::class);
    
    // Routes pour les filières avec route d'exportation
    Route::get('/fields/{field}/report', [FieldController::class, 'report'])->name('fields.report');
    Route::resource('fields', FieldController::class);
    
    // Routes pour les étudiants avec route d'exportation
    Route::get('/students/{student}/report', [StudentController::class, 'report'])->name('students.report');
    Route::resource('students', StudentController::class);
    
    // Routes pour la gestion des paiements
    Route::get('/payments/student-remaining/{student}', [PaymentController::class, 'getStudentRemainingAmount'])
        ->name('payments.student-remaining');
    
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
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        // Page principale des rapports
        Route::get('/', function () {
            return view('reports.index');
        })->name('index');
        
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

    // Routes pour le profil utilisateur
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');

    // Routes pour les paramètres de l'application
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.appearance');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
        Route::put('/language', [SettingsController::class, 'updateLanguage'])->name('settings.language');
        Route::put('/export', [SettingsController::class, 'updateExport'])->name('settings.export');
        Route::put('/advanced', [SettingsController::class, 'updateAdvanced'])->name('settings.advanced');
    });
});
