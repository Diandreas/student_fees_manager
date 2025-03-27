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
        $this->middleware('school');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $school = session('currentSchool');
        
        // Statistiques de base
        $totalStudents = Student::count();
        $totalPayments = Payment::sum('amount');
        $totalFields = Field::count();
        $totalCampuses = Campus::count();

        // Calcul des statistiques de paiement par étudiant
        $studentPaymentStats = DB::table('students')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->leftJoin('payments', 'students.id', '=', 'payments.student_id')
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
            ->select('campuses.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('campuses.name')
            ->get();

        // Statistiques mensuelles
        $monthlyStats = $this->getMonthlyStats();

        // Paiements récents avec détails
        $recentPayments = Payment::with(['student', 'student.field', 'student.field.campus'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        // Statistiques quotidiennes
        $todayStats = $this->getTodayStats();

        // Filières les plus populaires
        $popularFields = Field::withCount('students')
            ->orderBy('students_count', 'desc')
            ->limit(5)
            ->get();

        // Préparer les données pour les graphiques
        $chartData = [
            'paymentStatus' => [
                'labels' => ['Payé intégralement', 'Partiellement payé', 'Aucun paiement'],
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
            'chartData'
        ));
    }

    /**
     * Get monthly payment statistics for the past 6 months.
     */
    private function getMonthlyStats()
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
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('YEAR(payment_date) as year')
        )
            ->whereDate('payment_date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->get();

        $monthlyPayments->each(function ($item) use ($months) {
            $date = Carbon::createFromDate($item->year, $item->month, 1);
            $key = $months->search(function ($m) use ($date) {
                return $m['month'] === $date->format('F Y');
            });

            if ($key !== false) {
                $months[$key]['value'] = (float) $item->total;
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
    private function getTodayStats()
    {
        $today = Carbon::today();
        $todayPayments = Payment::whereDate('payment_date', $today)->sum('amount');
        $todayStudents = Payment::whereDate('payment_date', $today)
            ->distinct('student_id')
            ->count('student_id');

        return [
            'payments' => $todayPayments,
            'students' => $todayStudents
        ];
    }

    /**
     * Get API statistics data.
     */
    public function getStatistics()
    {
        $today = Carbon::today();
        $lastWeek = Carbon::now()->subWeek();

        // Tendances hebdomadaires
        $weeklyTrend = Payment::whereBetween('payment_date', [$lastWeek, $today])
            ->selectRaw('DATE(payment_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get();

        // Répartition par montant
        $paymentRanges = [
            '0-50000' => [0, 50000],
            '50000-100000' => [50000, 100000],
            '100000+' => [100000, PHP_FLOAT_MAX]
        ];

        $paymentDistribution = [];
        foreach ($paymentRanges as $label => $range) {
            $paymentDistribution[$label] = Payment::whereBetween('amount', $range)->count();
        }

        return response()->json([
            'today_stats' => $this->getTodayStats(),
            'weekly_trend' => $weeklyTrend,
            'payment_distribution' => $paymentDistribution,
            'recent_activities' => Payment::with(['student', 'student.field'])
                ->whereDate('payment_date', $today)
                ->latest()
                ->limit(5)
                ->get()
        ]);
    }
}
