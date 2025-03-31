<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class YearlyStat extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'school_id',
        'academic_year',
        'total_students',
        'new_students',
        'graduated_students',
        'total_invoiced',
        'total_paid',
        'total_remaining',
        'recovery_rate',
        'campus_stats',
        'field_stats',
        'monthly_payments',
        'archive_id'
    ];

    protected $casts = [
        'campus_stats' => 'array',
        'field_stats' => 'array',
        'monthly_payments' => 'array',
        'recovery_rate' => 'float',
    ];

    /**
     * Relation avec l'école
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relation avec l'archive
     */
    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    /**
     * Calculer le taux de recouvrement
     */
    public function calculateRecoveryRate()
    {
        if ($this->total_invoiced > 0) {
            $this->recovery_rate = ($this->total_paid / $this->total_invoiced) * 100;
        } else {
            $this->recovery_rate = 0;
        }
        return $this->recovery_rate;
    }

    /**
     * Récupérer les données mensuelles formatées pour les graphiques
     */
    public function getChartData()
    {
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        $monthlyPayments = $this->monthly_payments ?? [];

        $labels = [];
        $data = [];

        foreach ($months as $index => $month) {
            $labels[] = $month;
            $data[] = $monthlyPayments[$index + 1] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Récupérer les données par campus formatées pour les graphiques
     */
    public function getCampusChartData()
    {
        $campusStats = $this->campus_stats ?? [];

        $labels = [];
        $data = [];
        $colors = [];

        $defaultColors = [
            '#4B5563', '#EF4444', '#F59E0B', '#10B981', '#3B82F6',
            '#6366F1', '#8B5CF6', '#EC4899', '#F43F5E', '#14B8A6'
        ];

        $i = 0;
        foreach ($campusStats as $campus => $amount) {
            $labels[] = $campus;
            $data[] = $amount;
            $colors[] = $defaultColors[$i % count($defaultColors)];
            $i++;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        ];
    }
} 