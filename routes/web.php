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
// use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\QuickPaymentController;

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
Route::prefix('register')->group(function () {
    Route::get('/school', [SchoolRegisterController::class, 'showRegistrationForm'])->name('school.register');
    Route::post('/school', [SchoolRegisterController::class, 'register']);
});

// Routes protégées par l'authentification
Route::middleware(['auth'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password');
        Route::put('/preferences', 'updatePreferences')->name('preferences');
    });
    
    // Gestion des écoles
    Route::prefix('schools')->name('schools.')->group(function () {
        Route::get('/select', [SchoolController::class, 'select'])->name('select');
        Route::post('/{school}/switch', [SchoolController::class, 'switchSchool'])->name('switch');
    });
    
    // Statistiques API
    Route::get('/api/statistics', [DashboardController::class, 'getStatistics'])->name('api.statistics');
    
    // API pour les fonctionnalités mobiles
    Route::prefix('api')->group(function () {
        Route::get('/students/search', [QuickPaymentController::class, 'searchStudents']);
        Route::post('/payments/sync', [QuickPaymentController::class, 'syncOfflinePayments']);
    });
    
    // Routes pour les paiements rapides
    Route::get('/payments/quick', [QuickPaymentController::class, 'index'])->name('payments.quick');
    
    // Gestion des archives - disponible pour toutes les écoles de l'utilisateur
    Route::resource('archives', ArchiveController::class)->except(['edit', 'update']);
    Route::prefix('archives')->name('archives.')->controller(ArchiveController::class)->group(function () {
        Route::get('/{archive}/download', 'download')->name('download');
        Route::post('/{archive}/cleanup', 'cleanup')->name('cleanup');
    });
    
    // Routes protégées par la sélection d'une école
    Route::middleware(['school'])->group(function () {
        // Gestion des écoles
        Route::resource('schools', SchoolController::class)->except(['show']);
        Route::get('/schools/{school}', [SchoolController::class, 'show'])->name('schools.show');
        
        // Paramètres des écoles
        Route::get('/schools/{school}/settings', [SchoolSettingsController::class, 'index'])->name('schools.settings.index');
        Route::prefix('schools/{school}/settings')->name('schools.settings.')->controller(SchoolSettingsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'updateGeneral')->name('update');
            Route::put('/general', 'updateGeneral')->name('general');
            Route::get('/general', 'index')->name('general.edit');
            Route::put('/contact', 'updateContact')->name('contact');
            Route::get('/contact', 'showContactForm')->name('contact.edit');
            Route::put('/billing', 'updateBilling')->name('billing');
            Route::get('/billing', 'showBillingForm')->name('billing.edit');
            Route::put('/logo', 'updateLogo')->name('logo');
            Route::get('/logo', 'showLogoForm')->name('logo.edit');
            Route::put('/status', 'updateStatus')->name('status');
            Route::get('/status', 'showStatusForm')->name('status.edit');
            Route::put('/header', 'updateDocumentHeader')->name('header');
            Route::get('/header', 'showHeaderForm')->name('header.edit');
        });
        
        // Administrateurs d'école
        Route::prefix('schools/{school}/admins')->name('schools.admins.')->controller(SchoolAdminController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{admin}/edit', 'edit')->name('edit');
            Route::put('/{admin}', 'update')->name('update');
            Route::delete('/{admin}', 'destroy')->name('destroy');
        });
        
        // Gestion des campus
        Route::resource('campuses', CampusController::class);
        Route::prefix('campuses')->name('campuses.')->controller(CampusController::class)->group(function () {
            Route::get('/{campus}/solvable', 'downloadSolvableStudents')->name('solvable');
            Route::get('/{campus}/insolvable', 'downloadInsolvableStudents')->name('insolvable');
        });
        
        // Gestion des niveaux d'éducation
        Route::resource('education-levels', EducationLevelController::class);
        
        // Gestion des filières
        Route::resource('fields', FieldController::class);
        Route::prefix('fields')->name('fields.')->controller(FieldController::class)->group(function () {
            Route::get('/{field}/report', 'report')->name('report');
            Route::get('/{field}/solvable', 'downloadSolvableStudents')->name('solvable');
            Route::get('/{field}/insolvable', 'downloadInsolvableStudents')->name('insolvable');
        });
        
        // Gestion des étudiants
        Route::resource('students', StudentController::class);
        Route::prefix('students')->name('students.')->controller(StudentController::class)->group(function () {
            Route::get('/print', 'printList')->name('print');
            Route::get('/{student}/report', 'report')->name('report');
        });
        
        // Gestion des paiements
        Route::resource('payments', PaymentController::class);
        Route::prefix('payments')->name('payments.')->controller(PaymentController::class)->group(function () {
            Route::get('/student-remaining/{student}', 'getStudentRemainingAmount')->name('student-remaining');
            Route::get('/student-info/{student}', 'getStudentPaymentInfoApi')->name('student-info');
            Route::get('/print-list', 'printList')->name('print-list');
            Route::get('/export/{student_id?}', 'exportExcel')->name('export');
            Route::get('/export-excel/{student_id?}', 'exportExcel')->name('export-excel');
            Route::get('/{payment}/print', 'printReceipt')->name('print');
        });
        
        // Journal des activités
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show', 'destroy']);
        
        // Rapports et tableaux de bord
        Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/students', 'students')->name('students');
            Route::get('/payments', 'payments')->name('payments');
            Route::get('/finances', 'finances')->name('finances');
            Route::get('/performance', 'performance')->name('performance');
            
            // Exportations
            Route::prefix('export')->name('export.')->group(function () {
                Route::get('/students', 'exportStudents')->name('students');
                Route::get('/payments', 'exportPayments')->name('payments');
                Route::get('/finances', 'exportFinances')->name('finances');
            });

            // PDF
            Route::prefix('pdf')->name('pdf.')->group(function () {
                Route::get('/students', 'studentsPdf')->name('students');
                Route::get('/payments', 'paymentsPdf')->name('payments');
                Route::get('/finances', 'financesPdf')->name('finances');
            });
        });
        
        // Statistiques
        Route::prefix('statistics')->name('statistics.')->controller(StatisticsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/year/{year}', 'yearDetails')->name('year');
            Route::get('/compare', 'compare')->name('compare');
        });
    });
});
