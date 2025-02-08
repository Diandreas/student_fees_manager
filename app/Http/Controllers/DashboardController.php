<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $totalStudents = Student::count();
        $totalPayments = Payment::sum('amount');
        $totalFields = Field::count();

        // Calculate outstanding fees
        $outstandingFees = DB::table('students')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->leftJoin('payments', 'students.id', '=', 'payments.student_id')
            ->select(
                'students.id',
                'fields.fees as total_fees',
                DB::raw('COALESCE(SUM(payments.amount), 0) as paid_amount')
            )
            ->groupBy('students.id', 'fields.fees')
            ->get()
            ->sum(function ($student) {
                return max(0, $student->total_fees - $student->paid_amount);
            });

        // Get recent payments
        $recentPayments = Payment::with(['student', 'student.field'])
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Get monthly payment statistics for the current year using SQLite compatible syntax
        $monthlyPayments = Payment::selectRaw("strftime('%m', payment_date) as month, SUM(amount) as total")
            ->whereRaw("strftime('%Y', payment_date) = ?", [Carbon::now()->year])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Format monthly data for chart
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            // Format month number to match SQLite's 2-digit format (01, 02, etc.)
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyLabels[] = Carbon::create()->month($i)->format('M');
            $monthlyData[] = $monthlyPayments[$monthKey] ?? 0;
        }

        // Get payment status distribution
        $paymentStatus = DB::table('students')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->leftJoin('payments', 'students.id', '=', 'payments.student_id')
            ->select(
                'students.id',
                'fields.fees as total_fees',
                DB::raw('COALESCE(SUM(payments.amount), 0) as paid_amount')
            )
            ->groupBy('students.id', 'fields.fees')
            ->get()
            ->groupBy(function ($student) {
                if ($student->paid_amount >= $student->total_fees) return 'Paid';
                if ($student->paid_amount > 0) return 'Partial';
                return 'Unpaid';
            })
            ->map->count();

        return view('dashboard', compact(
            'totalStudents',
            'totalPayments',
            'totalFields',
            'outstandingFees',
            'recentPayments',
            'monthlyLabels',
            'monthlyData',
            'paymentStatus'
        ));
    }

    public function getStatistics()
    {
        $today = Carbon::today();

        return response()->json([
            'today_payments' => Payment::whereRaw("date(payment_date) = ?", [$today->format('Y-m-d')])->sum('amount'),
            'today_students' => Student::whereRaw("date(created_at) = ?", [$today->format('Y-m-d')])->count(),
            'recent_activities' => Payment::with('student')
                ->whereRaw("date(payment_date) = ?", [$today->format('Y-m-d')])
                ->latest()
                ->limit(5)
                ->get()
        ]);
    }
}
