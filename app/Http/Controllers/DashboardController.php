<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Field;
use App\Models\Payment;
use App\Models\Student;
use App\Models\School;
use App\Models\ActivityLog;
use App\Models\EducationLevel;
use App\Services\PaymentStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Constante pour la conversion
    const CURRENCY_SYMBOL = 'FCFA';
    
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $school = session('current_school');
        
        if (!$school) {
            // Si aucune école n'est sélectionnée, on essaie d'en trouver une
            if ($user->is_superadmin) {
                $school = School::first();
            } else {
                $schoolId = DB::table('school_admins')
                    ->where('user_id', $user->id)
                    ->value('school_id');
                
                if ($schoolId) {
                    $school = School::find($schoolId);
                }
            }
            
            if ($school) {
                session(['current_school_id' => $school->id]);
                session(['current_school' => $school]);
            } else {
                return redirect()->route('schools.select')
                    ->with('error', 'Veuillez sélectionner une école pour accéder au tableau de bord.');
            }
        }
        
        // Statistiques de base spécifiques à l'école actuelle
        $schoolId = $school->id;
        
        // Obtenir les campus de l'école actuelle
        $campusIds = Campus::where('school_id', $schoolId)->pluck('id')->toArray();
        
        // Obtenir les filières associées à ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Compter les étudiants associés à ces filières
        $totalStudents = Student::whereIn('field_id', $fieldIds)->count();
        
        // Obtenir les paiements des étudiants de cette école
        $totalPayments = Payment::whereHas('student', function($query) use ($fieldIds) {
            $query->whereIn('field_id', $fieldIds);
        })->sum('amount');
        
        // Compter le nombre de paiements
        $paymentsCount = Payment::whereHas('student', function($query) use ($fieldIds) {
            $query->whereIn('field_id', $fieldIds);
        })->count();
        
        // Nombre total de filières et de campus - Correction du comptage
        $totalFields = Field::whereIn('campus_id', $campusIds)->count();
        $totalCampuses = Campus::where('school_id', $schoolId)->count();
        
        // Calculer les montants attendus (frais de scolarité total)
        $totalExpectedFees = 0;
        
        $fields = Field::whereIn('id', $fieldIds)->with('students')->get();
        foreach ($fields as $field) {
            $totalExpectedFees += $field->fees * $field->students->count();
        }
        
        // Montant restant à recouvrer
        $outstandingFees = max(0, $totalExpectedFees - $totalPayments);
        
        // Statistiques des étudiants par statut de paiement
        $studentPaymentStatus = $this->getStudentPaymentStatus($fieldIds);
        
        // Statut de paiement pour les graphiques
        $paymentStatus = [
            'fully_paid' => $studentPaymentStatus['fully_paid'],
            'partial_paid' => $studentPaymentStatus['partial_paid'],
            'no_payment' => $studentPaymentStatus['no_payment'],
        ];
        
        // Taux de recouvrement
        $recoveryRate = $totalExpectedFees > 0
            ? round(($totalPayments / $totalExpectedFees) * 100, 2)
            : 0;

        // Paiements par campus
        $paymentsByCampus = DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
            ->where('campuses.school_id', $schoolId)
            ->select('campuses.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('campuses.name')
            ->get();

        // Statistiques mensuelles
        $monthlyStats = $this->getMonthlyStats($schoolId);

        // Paiements récents avec détails
        $recentPayments = Payment::with(['student', 'student.field', 'student.field.campus'])
            ->whereHas('student.field.campus', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        // Statistiques quotidiennes
        $todayStats = $this->getTodayStats($schoolId);

        // Filières les plus populaires
        $popularFields = Field::withCount('students')
            ->whereIn('campus_id', $campusIds)
            ->orderBy('students_count', 'desc')
            ->limit(5)
            ->get();

        // Obtenir les étudiants insolvables (avec des paiements incomplets)
        $insolvableStudents = $this->getInsolvableStudents($fieldIds);

        // Tendance des paiements par jour du mois
        $paymentTrends = $this->getPaymentTrendsByDay($fieldIds);

        // Préparer les données pour les graphiques
        $chartData = [
            'paymentStatus' => [
                'labels' => [
                    $school->term('fully_paid', 'Payé intégralement'), 
                    $school->term('partially_paid', 'Partiellement payé'), 
                    $school->term('no_payment', 'Aucun paiement')
                ],
                'data' => [
                    $paymentStatus['fully_paid'],
                    $paymentStatus['partial_paid'],
                    $paymentStatus['no_payment']
                ]
            ],
            'monthly' => $monthlyStats,
            'campusData' => [
                'labels' => $paymentsByCampus->pluck('name'),
                'data' => $paymentsByCampus->pluck('total')
            ],
            'paymentTrends' => [
                'labels' => $paymentTrends->pluck('day'),
                'data' => $paymentTrends->pluck('count')
            ]
        ];

        // Récupérer les statistiques par étudiant pour les calculs de recouvrement
        $studentsCount = $totalStudents; // Pour les calculs de pourcentage

        // Dernières activités de l'école
        $recentActivities = ActivityLog::where(function($query) use ($school) {
            $query->whereHasMorph('model', [Student::class, Payment::class], function($q) use ($school) {
                $q->where('school_id', $school->id);
            });
        })
        ->with(['user', 'model'])
        ->latest()
        ->take(5)
        ->get();

        return view('dashboard', compact(
            'user',
            'school',
            'totalStudents',
            'totalPayments',
            'totalFields',
            'totalCampuses',
            'outstandingFees',
            'recoveryRate',
            'paymentStatus',
            'recentPayments',
            'todayStats',
            'popularFields',
            'chartData',
            'totalExpectedFees',
            'studentsCount',
            'paymentsCount',
            'recentActivities',
            'insolvableStudents'
        ));
    }

    /**
     * Get monthly payment statistics for the past 6 months.
     */
    private function getMonthlyStats($schoolId)
    {
        $today = Carbon::today();
        $sixMonthsAgo = $today->copy()->subMonths(6);
        
        $labels = [];
        $data = [];
        
        // Utiliser notre service pour récupérer les statistiques de paiement
        $payments = \App\Services\PaymentStatistics::getMonthlyPaymentStats($schoolId, $sixMonthsAgo, $today);
        
        $months = [
            '01' => 'Jan',
            '02' => 'Fév',
            '03' => 'Mar',
            '04' => 'Avr',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juil',
            '08' => 'Août',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Déc'
        ];
        
        // Initialiser tous les mois à 0
        for ($i = 0; $i < 6; $i++) {
            $date = $sixMonthsAgo->copy()->addMonths($i);
            $monthKey = $date->format('m');
            $yearMonth = $months[$monthKey] . ' ' . $date->year;
            
            $labels[] = $yearMonth;
            $data[] = 0;
        }
        
        // Remplir avec les données réelles
        foreach ($payments as $payment) {
            for ($i = 0; $i < 6; $i++) {
                $date = $sixMonthsAgo->copy()->addMonths($i);
                
                if ($payment->year == $date->year && $payment->month == $date->format('m')) {
                    $data[$i] = $payment->total;
                    break;
                }
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get today's payment statistics.
     */
    private function getTodayStats($schoolId)
    {
        $today = Carbon::today();
        
        $todayPayments = Payment::whereDate('payment_date', $today)
            ->whereHas('student.field.campus', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->sum('amount');
        
        $todayStudents = Payment::whereDate('payment_date', $today)
            ->whereHas('student.field.campus', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->distinct('student_id')
            ->count('student_id');
        
        return [
            'payments' => $todayPayments,
            'students' => $todayStudents
        ];
    }

    /**
     * Get payment status for all students in school
     */
    private function getStudentPaymentStatus($fieldIds)
    {
        $students = Student::whereIn('field_id', $fieldIds)->get();
        
        $fullyPaid = 0;
        $partialPaid = 0;
        $noPaid = 0;
        
        foreach ($students as $student) {
            $field = Field::find($student->field_id);
            $totalFees = $field ? $field->fees : 0;
            
            $paidAmount = Payment::where('student_id', $student->id)->sum('amount');
            
            if ($paidAmount >= $totalFees) {
                $fullyPaid++;
            } elseif ($paidAmount > 0) {
                $partialPaid++;
            } else {
                $noPaid++;
            }
        }
        
        return [
            'fully_paid' => $fullyPaid,
            'partial_paid' => $partialPaid,
            'no_payment' => $noPaid
        ];
    }

    /**
     * Get insolvable students (with incomplete payments)
     */
    private function getInsolvableStudents($fieldIds)
    {
        $students = Student::whereIn('field_id', $fieldIds)
            ->with(['field', 'payments'])
            ->get()
            ->filter(function ($student) {
                $field = $student->field;
                $totalFees = $field ? $field->fees : 0;
                $paidAmount = $student->payments->sum('amount');
                $student->outstanding_fees = max(0, $totalFees - $paidAmount);
                
                return $paidAmount < $totalFees && $paidAmount > 0;
            })
            ->sortByDesc('outstanding_fees')
            ->values();
        
        return $students;
    }

    /**
     * Get payment trends by day of month
     */
    private function getPaymentTrendsByDay($fieldIds)
    {
        // Utiliser le service pour récupérer les tendances de paiement
        $paymentTrends = \App\Services\PaymentStatistics::getPaymentTrendsByDay($fieldIds);
        
        return $paymentTrends->sortBy('day')->values();
    }

    /**
     * Get general statistics for API
     */
    public function getStatistics()
    {
        $user = Auth::user();
        $school = session('current_school');
        
        if (!$school) {
            return response()->json([
                'error' => 'No school selected'
            ], 400);
        }
        
        $schoolId = $school->id;
        
        // Obtenir les campus de l'école actuelle
        $campusIds = Campus::where('school_id', $schoolId)->pluck('id')->toArray();
        
        // Obtenir les filières associées à ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Statistiques de base
        $totalStudents = Student::whereIn('field_id', $fieldIds)->count();
        
        $totalPayments = Payment::whereHas('student', function($query) use ($fieldIds) {
            $query->whereIn('field_id', $fieldIds);
        })->sum('amount');
        
        $totalFields = count($fieldIds);
        $totalCampuses = count($campusIds);
        
        // Plus d'informations statistiques spécifiques à l'API
        $stats = [
            'total_students' => $totalStudents,
            'total_payments' => $totalPayments,
            'total_fields' => $totalFields,
            'total_campuses' => $totalCampuses,
            'school_name' => $school->name,
            'school_id' => $school->id
        ];
        
        return response()->json($stats);
    }
}
