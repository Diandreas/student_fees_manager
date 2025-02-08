<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Field;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Constante pour la conversion
    const CURRENCY_SYMBOL = 'FCFA';

    public function index()
    {
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
            ->join('campuses', 'fields.campus_id', '=', 'campuses.id') // 'campus' -> 'campuses'
            ->select('campuses.name', DB::raw('SUM(payments.amount) as total')) // 'campus' -> 'campuses'
            ->groupBy('campuses.name') // 'campus' -> 'campuses'
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

    private function getMonthlyStats()
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();

        return Payment::selectRaw("
                strftime('%m', payment_date) as month,
                COUNT(*) as count,
                SUM(amount) as total,
                AVG(amount) as average
            ")
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $month = Carbon::createFromFormat('m', $item->month)->format('M');
                return [
                    'month' => $month,
                    'count' => $item->count,
                    'total' => $item->total,
                    'average' => round($item->average, 2)
                ];
            });
    }

    private function getTodayStats()
    {
        $today = Carbon::today();

        return [
            'payments' => Payment::whereDate('payment_date', $today)->sum('amount'),
            'new_students' => Student::whereDate('created_at', $today)->count(),
            'payment_count' => Payment::whereDate('payment_date', $today)->count(),
            'average_payment' => Payment::whereDate('payment_date', $today)->avg('amount') ?? 0
        ];
    }

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
