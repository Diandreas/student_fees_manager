<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentStatistics 
{
    /**
     * Récupérer les statistiques de paiement mensuelles pour une école spécifique
     *
     * @param int $schoolId L'ID de l'école
     * @param Carbon|null $startDate Date de début (optionnelle)
     * @param Carbon|null $endDate Date de fin (optionnelle)
     * @return \Illuminate\Support\Collection
     */
    public static function getMonthlyPaymentStats($schoolId, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subMonths(12);
        }

        if (!$endDate) {
            $endDate = Carbon::now();
        }

        return DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
            ->where('campuses.school_id', $schoolId)
            ->where('payments.payment_date', '>=', $startDate)
            ->select(
                DB::raw("strftime('%Y', payments.payment_date) as year"),
                DB::raw("strftime('%m', payments.payment_date) as month"),
                DB::raw('SUM(payments.amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    /**
     * Obtenir les tendances de paiement par jour du mois
     *
     * @param array $fieldIds Les IDs des filières
     * @param int $months Nombre de mois à remonter
     * @return \Illuminate\Support\Collection
     */
    public static function getPaymentTrendsByDay($fieldIds, $months = 1)
    {
        $pastDate = Carbon::now()->subMonths($months);
        
        $payments = DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->whereIn('students.field_id', $fieldIds)
            ->where('payments.payment_date', '>=', $pastDate)
            ->select(
                DB::raw("strftime('%d', payments.payment_date) as day"),
                DB::raw("COUNT(*) as count")
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        
        // Préparer un tableau pour tous les jours du mois
        $daysArray = [];
        for ($i = 1; $i <= 31; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $daysArray[$day] = 0;
        }
        
        // Remplir avec les données réelles
        foreach ($payments as $payment) {
            $daysArray[$payment->day] = $payment->count;
        }
        
        // Convertir en collection pour un traitement plus facile
        $dayTrends = collect();
        
        // Conserver seulement les jours avec des valeurs et tous les 5 jours
        foreach ($daysArray as $day => $count) {
            $dayNumber = (int)$day;
            if ($count > 0 || $dayNumber % 5 === 0 || $dayNumber === 1 || $dayNumber === 31) {
                $dayTrends->push([
                    'day' => $day,
                    'count' => $count
                ]);
            }
        }
        
        return $dayTrends;
    }

    /**
     * Récupérer les statistiques de paiement par année et mois pour une école donnée
     * avec une date de début spécifique
     *
     * @param int $schoolId L'ID de l'école
     * @param string $startDate Date de début au format 'Y-m-d'
     * @return \Illuminate\Support\Collection
     */
    public static function getPaymentStatsSince($schoolId, $startDate)
    {
        return DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
            ->where('campuses.school_id', $schoolId)
            ->where('payments.payment_date', '>=', $startDate)
            ->select(
                DB::raw("strftime('%Y', payments.payment_date) as year"),
                DB::raw("strftime('%m', payments.payment_date) as month"),
                DB::raw('SUM(payments.amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }
} 