<?php

namespace App\Http\Controllers;

use App\Models\YearlyStat;
use App\Models\Archive;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Afficher les statistiques pluriannuelles
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = auth()->user()->school;
        
        // Récupérer les statistiques annuelles de l'école
        $yearlyStats = YearlyStat::where('school_id', $school->id)
                                ->orderBy('academic_year', 'desc')
                                ->get();
        
        // Récupérer les archives correspondantes
        $archives = Archive::where('school_id', $school->id)
                          ->orderBy('academic_year', 'desc')
                          ->get();
        
        // Préparer les données pour les graphiques
        $chartData = $this->prepareChartData($yearlyStats);
        
        return view('statistics.index', compact('yearlyStats', 'archives', 'chartData'));
    }
    
    /**
     * Afficher les statistiques détaillées d'une année spécifique
     *
     * @param  string  $year  L'année académique (ex: 2023-2024)
     * @return \Illuminate\Http\Response
     */
    public function yearDetails($year)
    {
        $school = auth()->user()->school;
        
        // Récupérer les statistiques de l'année spécifiée
        $yearlyStat = YearlyStat::where('school_id', $school->id)
                               ->where('academic_year', $year)
                               ->firstOrFail();
        
        // Récupérer l'archive correspondante si elle existe
        $archive = Archive::where('school_id', $school->id)
                         ->where('academic_year', $year)
                         ->first();
        
        // Préparer les données pour les graphiques mensuels
        $monthlyChartData = $yearlyStat->getChartData();
        
        // Préparer les données pour les graphiques par campus
        $campusChartData = $yearlyStat->getCampusChartData();
        
        return view('statistics.year-details', compact(
            'yearlyStat', 
            'archive', 
            'monthlyChartData', 
            'campusChartData',
            'year'
        ));
    }
    
    /**
     * Comparer les statistiques entre différentes années
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function compare(Request $request)
    {
        $school = auth()->user()->school;
        
        // Récupérer toutes les années académiques disponibles
        $availableYears = YearlyStat::where('school_id', $school->id)
                                  ->orderBy('academic_year', 'desc')
                                  ->pluck('academic_year')
                                  ->toArray();
        
        $selectedYears = $request->input('years', []);
        
        if (empty($selectedYears) && !empty($availableYears)) {
            // Sélectionner les deux dernières années par défaut si disponibles
            $selectedYears = array_slice($availableYears, 0, min(2, count($availableYears)));
        }
        
        // Récupérer les statistiques des années sélectionnées
        $yearlyStats = YearlyStat::where('school_id', $school->id)
                                ->whereIn('academic_year', $selectedYears)
                                ->orderBy('academic_year')
                                ->get();
        
        // Préparer les données pour les graphiques comparatifs
        $comparisonData = $this->prepareComparisonData($yearlyStats);
        
        return view('statistics.compare', compact('availableYears', 'selectedYears', 'yearlyStats', 'comparisonData'));
    }
    
    /**
     * Préparer les données pour les graphiques pluriannuels
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $yearlyStats
     * @return array
     */
    private function prepareChartData($yearlyStats)
    {
        $years = [];
        $totalPaid = [];
        $totalInvoiced = [];
        $recoveryRates = [];
        $newStudents = [];
        
        foreach ($yearlyStats as $stat) {
            $years[] = $stat->academic_year;
            $totalPaid[] = $stat->total_paid;
            $totalInvoiced[] = $stat->total_invoiced;
            $recoveryRates[] = $stat->recovery_rate;
            $newStudents[] = $stat->new_students;
        }
        
        // Inverser les tableaux pour afficher les années dans l'ordre chronologique
        $years = array_reverse($years);
        $totalPaid = array_reverse($totalPaid);
        $totalInvoiced = array_reverse($totalInvoiced);
        $recoveryRates = array_reverse($recoveryRates);
        $newStudents = array_reverse($newStudents);
        
        return [
            'years' => $years,
            'totalPaid' => $totalPaid,
            'totalInvoiced' => $totalInvoiced,
            'recoveryRates' => $recoveryRates,
            'newStudents' => $newStudents
        ];
    }
    
    /**
     * Préparer les données pour les graphiques comparatifs
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $yearlyStats
     * @return array
     */
    private function prepareComparisonData($yearlyStats)
    {
        $comparisonData = [
            'years' => [],
            'monthlyPayments' => [],
            'campusComparison' => [],
            'financialMetrics' => [
                'labels' => ['Total facturé', 'Total payé', 'Montant restant', 'Taux de recouvrement (%)'],
                'datasets' => []
            ]
        ];
        
        $colors = [
            '#3B82F6', // Bleu
            '#EF4444', // Rouge
            '#10B981', // Vert
            '#F59E0B', // Orange
            '#8B5CF6', // Violet
            '#EC4899', // Rose
        ];
        
        foreach ($yearlyStats as $index => $stat) {
            $year = $stat->academic_year;
            $comparisonData['years'][] = $year;
            $color = $colors[$index % count($colors)];
            
            // Données mensuelles
            $monthlyData = $stat->getChartData();
            $comparisonData['monthlyPayments'][$year] = [
                'data' => $monthlyData['data'],
                'color' => $color
            ];
            
            // Données financières pour comparaison
            $comparisonData['financialMetrics']['datasets'][] = [
                'label' => $year,
                'data' => [
                    $stat->total_invoiced,
                    $stat->total_paid,
                    $stat->total_remaining,
                    $stat->recovery_rate
                ],
                'backgroundColor' => $color
            ];
            
            // Statistiques par campus
            $campusData = $stat->getCampusChartData();
            $comparisonData['campusComparison'][$year] = [
                'labels' => $campusData['labels'],
                'data' => $campusData['data'],
                'colors' => $campusData['colors']
            ];
        }
        
        return $comparisonData;
    }
} 