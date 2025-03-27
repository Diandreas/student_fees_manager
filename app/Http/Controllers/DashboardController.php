<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Field;
use App\Models\Payment;
use App\Models\Student;
use App\Models\School;
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
        
        $totalFields = Field::whereIn('campus_id', $campusIds)->count();
        $totalCampuses = count($campusIds);

        // Calcul des statistiques de paiement par étudiant
        $studentPaymentStats = DB::table('students')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
            ->leftJoin('payments', 'students.id', '=', 'payments.student_id')
            ->where('campuses.school_id', $schoolId)
            ->select(
                'students.id',
                'fields.fees as total_fees',
                DB::raw('COALESCE(SUM(payments.amount), 0) as paid_amount')
            )
            ->groupBy('students.id', 'fields.fees')
            ->get();

        // Calcul des différentes statistiques
        $paymentStatus = [
            'fully_paid' => 0,
            'partial_paid' => 0,
            'no_payment' => 0
        ];

        $outstandingFees = 0;
        $totalExpectedFees = 0;

        foreach ($studentPaymentStats as $stat) {
            $totalExpectedFees += $stat->total_fees;
            $remaining = $stat->total_fees - $stat->paid_amount;
            $outstandingFees += max(0, $remaining);

            if ($stat->paid_amount >= $stat->total_fees) {
                $paymentStatus['fully_paid']++;
            } elseif ($stat->paid_amount > 0) {
                $paymentStatus['partial_paid']++;
            } else {
                $paymentStatus['no_payment']++;
            }
        }

        // Calcul du taux de recouvrement
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
            ]
        ];

        // Récupérer les statistiques par étudiant pour les calculs de recouvrement
        $studentsCount = $totalStudents; // Pour les calculs de pourcentage

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
            'studentsCount'
        ));
    }

    /**
     * Get monthly payment statistics for the past 6 months.
     */
    private function getMonthlyStats($schoolId)
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'month' => $date->format('F Y'),
                'value' => 0
            ]);
        }

        $monthlyPayments = Payment::select(
            DB::raw('SUM(amount) as total'),
            DB::raw("strftime('%m', payment_date) as month"),
            DB::raw("strftime('%Y', payment_date) as year")
        )
            ->whereHas('student.field.campus', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereDate('payment_date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->get();

        $monthlyPayments->each(function ($item) use (&$months) {
            $date = Carbon::createFromDate($item->year, $item->month, 1);
            $key = $months->search(function ($m) use ($date) {
                return $m['month'] === $date->format('F Y');
            });

            if ($key !== false) {
                // Créer un nouvel élément avec la valeur mise à jour
                $updatedItem = $months[$key];
                $updatedItem['value'] = (float) $item->total;
                
                // Remplacer l'élément dans la collection
                $months = $months->replace([$key => $updatedItem]);
            }
        });

        return [
            'labels' => $months->pluck('month'),
            'data' => $months->pluck('value')
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
