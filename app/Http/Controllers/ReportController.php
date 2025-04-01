<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Field;
use App\Models\Payment;
use App\Models\Student;
use App\Models\School;
use App\Services\PaymentStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la page d'accueil des rapports.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        return view('reports.index', compact('school'));
    }
    
    /**
     * Affiche le rapport des étudiants.
     *
     * @return \Illuminate\Http\Response
     */
    public function students()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Statistiques globales spécifiques à cette école
        $totalStudents = Student::whereIn('field_id', $fieldIds)->count();
        $activeStudents = Student::whereIn('field_id', $fieldIds)->count(); // Supposons que tous les étudiants sont actifs pour l'instant
        
        // Statistiques de paiement des étudiants de cette école
        $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        $totalFees = Field::whereIn('id', $fieldIds)
            ->join('students', 'fields.id', '=', 'students.field_id')
            ->sum('fields.fees');
            
        $totalPaid = Payment::whereIn('student_id', $studentIds)->sum('amount');
        
        $remainingAmount = max(0, $totalFees - $totalPaid);
        
        // Taux de recouvrement en pourcentage
        $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        
        // Statistiques de paiement
        $paymentStats = [
            'totalFees' => $totalFees,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount,
            'recoveryRate' => $recoveryRate,
        ];
        
        // Étudiants par filière (top 5)
        $studentsByField = DB::table('students')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->whereIn('fields.id', $fieldIds)
            ->select('fields.name', DB::raw('COUNT(students.id) as count'))
            ->groupBy('fields.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
            
        // Étudiants par campus
        $studentsByCampus = DB::table('students')
            ->join('fields', 'students.field_id', '=', 'fields.id')
            ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
            ->whereIn('fields.id', $fieldIds)
            ->select('campuses.name', DB::raw('COUNT(students.id) as count'))
            ->groupBy('campuses.name')
            ->orderBy('count', 'desc')
            ->get();
            
        return view('reports.students', compact(
            'totalStudents',
            'activeStudents',
            'paymentStats',
            'recoveryRate',
            'studentsByField',
            'studentsByCampus',
            'school'
        ));
    }

    /**
     * Affiche le rapport des paiements.
     *
     * @return \Illuminate\Http\Response
     */
    public function payments()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Obtenir les étudiants associés à ces filières
        $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Statistiques globales des paiements
        $totalPayments = Payment::whereIn('student_id', $studentIds)->sum('amount');
        $paymentsCount = Payment::whereIn('student_id', $studentIds)->count();
        $studentsCount = count($studentIds);
        $studentsWithPayments = Student::whereIn('id', $studentIds)
                                      ->whereHas('payments')
                                      ->count();
        
        // Paiements récents
        $recentPayments = Payment::with(['student.field.campus'])
                                ->whereIn('student_id', $studentIds)
                                ->orderBy('payment_date', 'desc')
                                ->limit(5)
                                ->get();
        
        // Paiements mensuels pour l'année en cours
        $currentYear = date('Y');
        $startDate = "{$currentYear}-01-01"; // Début de l'année en cours
        
        $monthlyData = \App\Services\PaymentStatistics::getPaymentStatsSince($school->id, $startDate);
        
        // Convertir les données pour l'affichage
        $monthsLabels = [
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
        
        $monthlyPayments = [];
        foreach (range(1, 12) as $i) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $amount = 0;
            
            // Chercher les données pour ce mois
            foreach ($monthlyData as $data) {
                if ($data->month === $month && $data->year === $currentYear) {
                    $amount = $data->total;
                    break;
                }
            }
            
            $monthlyPayments[] = [
                'month' => $monthsLabels[$month],
                'amount' => $amount
            ];
        }
        
        // Paiements par filière (top 5)
        $paymentsByField = DB::table('payments')
                            ->join('students', 'payments.student_id', '=', 'students.id')
                            ->join('fields', 'students.field_id', '=', 'fields.id')
                            ->whereIn('students.id', $studentIds)
                            ->select('fields.name', DB::raw('SUM(payments.amount) as total'))
                            ->groupBy('fields.name')
                            ->orderBy('total', 'desc')
                            ->limit(5)
                            ->get();
        
        // Paiements par campus
        $paymentsByCampus = DB::table('payments')
                            ->join('students', 'payments.student_id', '=', 'students.id')
                            ->join('fields', 'students.field_id', '=', 'fields.id')
                            ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
                            ->whereIn('students.id', $studentIds)
                            ->select('campuses.name', DB::raw('SUM(payments.amount) as total'))
                            ->groupBy('campuses.name')
                            ->orderBy('total', 'desc')
                            ->get();
        
        // Tous les paiements pour l'export
        $allPayments = Payment::whereIn('student_id', $studentIds)
                            ->with('student.field.campus')
                            ->orderBy('payment_date')
                            ->get();
                            
        return view('reports.payments', compact(
            'totalPayments',
            'recentPayments',
            'monthlyPayments',
            'paymentsByField',
            'paymentsByCampus',
            'allPayments',
            'school',
            'studentsCount',
            'paymentsCount',
            'studentsWithPayments'
        ));
    }

    /**
     * Affiche le rapport des finances.
     *
     * @return \Illuminate\Http\Response
     */
    public function finances()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Obtenir les étudiants associés à ces filières
        $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Montant total des frais
        $totalFees = Field::whereIn('id', $fieldIds)
            ->join('students', 'fields.id', '=', 'students.field_id')
            ->sum('fields.fees');
            
        // Montant total payé
        $totalPaid = Payment::whereIn('student_id', $studentIds)->sum('amount');
        
        // Montant restant à percevoir
        $remainingAmount = max(0, $totalFees - $totalPaid);
        
        // Taux de recouvrement en pourcentage
        $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        
        // Statistiques de paiement par mois
        $currentYear = date('Y');
        $startDate = "{$currentYear}-01-01"; // Début de l'année en cours
        
        $monthlyData = \App\Services\PaymentStatistics::getPaymentStatsSince($school->id, $startDate);
        
        // Convertir les données pour l'affichage
        $monthsLabels = [
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
        
        $monthlyStats = [];
        foreach (range(1, 12) as $i) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $amount = 0;
            
            // Chercher les données pour ce mois
            foreach ($monthlyData as $data) {
                if ($data->month === $month && $data->year === $currentYear) {
                    $amount = $data->total;
                    break;
                }
            }
            
            $monthlyStats[] = [
                'month' => $monthsLabels[$month],
                'amount' => $amount
            ];
        }
        
        return view('reports.finances', compact(
            'totalFees',
            'totalPaid',
            'remainingAmount',
            'recoveryRate',
            'monthlyStats',
            'school'
        ));
    }

    /**
     * Affiche le rapport des performances.
     *
     * @return \Illuminate\Http\Response
     */
    public function performance()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Statistiques par filière
        $fieldStats = Field::whereIn('id', $fieldIds)
            ->with(['campus', 'students.payments'])
            ->get()
            ->map(function ($field) {
                $totalStudents = $field->students->count();
                $totalFees = $totalStudents * $field->fees;
                $totalPaid = 0;
                
                foreach ($field->students as $student) {
                    $totalPaid += $student->payments->sum('amount');
                }
                
                $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
                
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'campus' => $field->campus->name,
                    'students' => $totalStudents,
                    'totalFees' => $totalFees,
                    'totalPaid' => $totalPaid,
                    'remainingAmount' => max(0, $totalFees - $totalPaid),
                    'recoveryRate' => $recoveryRate,
                ];
            })
            ->sortByDesc('recoveryRate')
            ->values();
            
        // Statistiques par campus
        $campusStats = Campus::whereIn('id', $campusIds)
            ->with(['fields.students.payments'])
            ->get()
            ->map(function ($campus) {
                $totalStudents = 0;
                $totalFees = 0;
                $totalPaid = 0;
                
                foreach ($campus->fields as $field) {
                    $fieldStudents = $field->students->count();
                    $totalStudents += $fieldStudents;
                    $totalFees += $fieldStudents * $field->fees;
                    
                    foreach ($field->students as $student) {
                        $totalPaid += $student->payments->sum('amount');
                    }
                }
                
                $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
                
                return [
                    'id' => $campus->id,
                    'name' => $campus->name,
                    'fields' => $campus->fields->count(),
                    'students' => $totalStudents,
                    'totalFees' => $totalFees,
                    'totalPaid' => $totalPaid,
                    'remainingAmount' => max(0, $totalFees - $totalPaid),
                    'recoveryRate' => $recoveryRate,
                ];
            })
            ->sortByDesc('recoveryRate')
            ->values();
            
        return view('reports.performance', compact(
            'fieldStats',
            'campusStats',
            'school'
        ));
    }

    /**
     * Exporte les données des étudiants au format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportStudents()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour exporter les données.');
        }
        
        // Code d'exportation ici
        
        return redirect()->route('reports.students')
            ->with('success', 'Export des étudiants en cours de développement.');
    }

    /**
     * Exporte les données des paiements au format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPayments()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour exporter les données.');
        }
        
        // Code d'exportation ici
        
        return redirect()->route('reports.payments')
            ->with('success', 'Export des paiements en cours de développement.');
    }

    /**
     * Exporte les données financières au format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportFinances()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour exporter les données.');
        }
        
        // Code d'exportation ici
        
        return redirect()->route('reports.finances')
            ->with('success', 'Export des finances en cours de développement.');
    }

    /**
     * Génère un PDF du rapport des étudiants
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function studentsPdf(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fields = Field::whereIn('campus_id', $campusIds)->get();
        $fieldIds = $fields->pluck('id')->toArray();
        
        // Filtrer par filière si spécifié
        $query = Student::with(['field', 'payments'])
                        ->whereIn('field_id', $fieldIds);
                        
        if ($request->has('field_id') && $request->field_id) {
            $query->where('field_id', $request->field_id);
        }
        
        // Filtrer par statut de paiement si spécifié
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'paid':
                    $query->whereHas('payments', function ($q) {
                        $q->havingRaw('SUM(amount) >= fields.fees');
                    });
                    break;
                case 'partial':
                    $query->whereHas('payments', function ($q) {
                        $q->havingRaw('SUM(amount) < fields.fees AND SUM(amount) > 0');
                    });
                    break;
                case 'unpaid':
                    $query->whereDoesntHave('payments');
                    break;
            }
        }
        
        $students = $query->get();
        
        // Génération du PDF avec DomPDF
        $pdf = Pdf::loadView('reports.pdf.students', compact('students', 'school'));
        
        return $pdf->download('rapport-etudiants-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Génère un PDF du rapport des paiements
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function paymentsPdf(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Obtenir les étudiants associés à ces filières
        $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Obtenir les paiements
        $query = Payment::with(['student.field'])
                         ->whereIn('student_id', $studentIds);
                         
        // Filtrer par période si spécifiée
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }
        
        $payments = $query->orderBy('payment_date', 'desc')->get();
        
        // Génération du PDF avec DomPDF
        $pdf = Pdf::loadView('reports.pdf.payments', compact('payments', 'school'));
        
        return $pdf->download('rapport-paiements-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Génère un PDF du rapport financier
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function financesPdf(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Obtenir les étudiants associés à ces filières
        $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Statistiques financières
        $totalFees = Field::whereIn('id', $fieldIds)
                          ->join('students', 'fields.id', '=', 'students.field_id')
                          ->sum('fields.fees');
                          
        $totalPaid = Payment::whereIn('student_id', $studentIds)->sum('amount');
        $remainingAmount = max(0, $totalFees - $totalPaid);
        $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        
        // Statistiques par filière
        $statsByField = Field::whereIn('id', $fieldIds)
                            ->select('fields.id', 'fields.name', 'fields.fees')
                            ->withCount('students')
                            ->get()
                            ->map(function ($field) {
                                $paidAmount = Payment::whereHas('student', function ($query) use ($field) {
                                                $query->where('field_id', $field->id);
                                             })->sum('amount');
                                             
                                $expectedAmount = $field->fees * $field->students_count;
                                $remainingAmount = max(0, $expectedAmount - $paidAmount);
                                $recoveryRate = $expectedAmount > 0 ? round(($paidAmount / $expectedAmount) * 100) : 0;
                                
                                return [
                                    'id' => $field->id,
                                    'name' => $field->name,
                                    'students_count' => $field->students_count,
                                    'fees' => $field->fees,
                                    'expected_amount' => $expectedAmount,
                                    'paid_amount' => $paidAmount,
                                    'remaining_amount' => $remainingAmount,
                                    'recovery_rate' => $recoveryRate
                                ];
                            });
        
        // Génération du PDF avec DomPDF
        $pdf = Pdf::loadView('reports.pdf.finances', compact(
            'totalFees', 
            'totalPaid', 
            'remainingAmount', 
            'recoveryRate', 
            'statsByField',
            'school'
        ));
        
        return $pdf->download('rapport-financier-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Affiche le rapport de résumé des paiements
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentSummary()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Récupérer les données de paiement regroupées par mois
        $currentYear = date('Y');
        $startDate = "{$currentYear}-01-01";
        
        $paymentStats = \App\Services\PaymentStatistics::getPaymentStatsSince($school->id, $startDate);
        
        // Formater les données pour le graphique
        $monthLabels = [];
        $paymentData = [];
        
        $monthsLabels = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre'
        ];
        
        foreach ($paymentStats as $stat) {
            $monthLabels[] = $monthsLabels[$stat->month] ?? $stat->month;
            $paymentData[] = $stat->total;
        }
        
        // Récupérer les statistiques globales
        $campusIds = $school->campuses()->pluck('id')->toArray();
        $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        $studentIds = \App\Models\Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        $totalPayments = \App\Models\Payment::whereIn('student_id', $studentIds)->sum('amount');
        $totalStudents = count($studentIds);
        $studentsWithPayments = \App\Models\Payment::whereIn('student_id', $studentIds)
                                    ->distinct('student_id')
                                    ->count('student_id');
                                    
        // Taux de paiement (étudiants ayant effectué au moins un paiement)
        $paymentRate = $totalStudents > 0 ? round(($studentsWithPayments / $totalStudents) * 100, 2) : 0;
        
        return view('reports.payment-summary', compact(
            'school',
            'monthLabels',
            'paymentData',
            'totalPayments',
            'totalStudents',
            'studentsWithPayments',
            'paymentRate'
        ));
    }
    
    /**
     * Affiche le rapport de performance par campus
     *
     * @return \Illuminate\Http\Response
     */
    public function campusPerformance()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Récupérer tous les campus de l'école
        $campuses = $school->campuses;
        
        // Données pour chaque campus
        $campusData = [];
        
        foreach ($campuses as $campus) {
            // Obtenir toutes les filières du campus
            $fieldIds = \App\Models\Field::where('campus_id', $campus->id)->pluck('id')->toArray();
            
            // Nombre d'étudiants
            $studentCount = \App\Models\Student::whereIn('field_id', $fieldIds)->count();
            
            // Montant total des frais
            $totalFees = \App\Models\Field::whereIn('id', $fieldIds)->sum('fees') * $studentCount;
            
            // Montant total des paiements
            $studentIds = \App\Models\Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
            $totalPaid = \App\Models\Payment::whereIn('student_id', $studentIds)->sum('amount');
            
            // Taux de recouvrement
            $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100, 2) : 0;
            
            $campusData[] = [
                'campus' => $campus,
                'studentCount' => $studentCount,
                'totalFees' => $totalFees,
                'totalPaid' => $totalPaid,
                'recoveryRate' => $recoveryRate
            ];
        }
        
        // Trier par taux de recouvrement décroissant
        usort($campusData, function($a, $b) {
            return $b['recoveryRate'] - $a['recoveryRate'];
        });
        
        return view('reports.campus-performance', compact(
            'school',
            'campusData'
        ));
    }
    
    /**
     * Affiche le rapport d'analyse par filière
     *
     * @return \Illuminate\Http\Response
     */
    public function fieldAnalysis()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Récupérer les campus de l'école
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Récupérer toutes les filières avec leurs statistiques
        $fields = \App\Models\Field::whereIn('campus_id', $campusIds)
                ->with('campus')
                ->get();
                
        $fieldData = [];
        
        foreach ($fields as $field) {
            // Nombre d'étudiants
            $studentCount = \App\Models\Student::where('field_id', $field->id)->count();
            
            // Montant total des frais
            $totalFees = $field->fees * $studentCount;
            
            // Montant total des paiements
            $studentIds = \App\Models\Student::where('field_id', $field->id)->pluck('id')->toArray();
            $totalPaid = \App\Models\Payment::whereIn('student_id', $studentIds)->sum('amount');
            
            // Taux de recouvrement
            $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100, 2) : 0;
            
            $fieldData[] = [
                'field' => $field,
                'studentCount' => $studentCount,
                'totalFees' => $totalFees,
                'totalPaid' => $totalPaid,
                'recoveryRate' => $recoveryRate
            ];
        }
        
        // Trier par taux de recouvrement décroissant
        usort($fieldData, function($a, $b) {
            return $b['recoveryRate'] - $a['recoveryRate'];
        });
        
        return view('reports.field-analysis', compact(
            'school',
            'fieldData'
        ));
    }
    
    /**
     * Affiche le rapport annuel
     *
     * @return \Illuminate\Http\Response
     */
    public function annual()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Année actuelle
        $currentYear = date('Y');
        
        // Statistiques mensuelles pour l'année en cours
        $monthlyStats = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $startDate = "{$currentYear}-{$monthStr}-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            // Trouver les campus de cette école
            $campusIds = $school->campuses()->pluck('id')->toArray();
            
            // Trouver les filières associées à ces campus
            $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
            
            // Trouver les étudiants associés à ces filières
            $studentIds = \App\Models\Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
            
            // Total des paiements pour ce mois
            $totalPayments = \App\Models\Payment::whereIn('student_id', $studentIds)
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount');
                
            // Nombre d'étudiants ayant effectué un paiement ce mois
            $studentsWithPayments = \App\Models\Payment::whereIn('student_id', $studentIds)
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->distinct('student_id')
                ->count('student_id');
                
            $monthsLabels = [
                '01' => 'Janvier',
                '02' => 'Février',
                '03' => 'Mars',
                '04' => 'Avril',
                '05' => 'Mai',
                '06' => 'Juin',
                '07' => 'Juillet',
                '08' => 'Août',
                '09' => 'Septembre',
                '10' => 'Octobre',
                '11' => 'Novembre',
                '12' => 'Décembre'
            ];
            
            $monthlyStats[] = [
                'month' => $monthsLabels[$monthStr],
                'totalPayments' => $totalPayments,
                'studentsWithPayments' => $studentsWithPayments
            ];
        }
        
        // Statistiques annuelles
        $startOfYear = "{$currentYear}-01-01";
        $endOfYear = "{$currentYear}-12-31";
        
        $campusIds = $school->campuses()->pluck('id')->toArray();
        $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        $studentIds = \App\Models\Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        $annualPayments = \App\Models\Payment::whereIn('student_id', $studentIds)
            ->whereBetween('payment_date', [$startOfYear, $endOfYear])
            ->sum('amount');
            
        $totalStudents = count($studentIds);
        $studentsWithPayments = \App\Models\Payment::whereIn('student_id', $studentIds)
            ->whereBetween('payment_date', [$startOfYear, $endOfYear])
            ->distinct('student_id')
            ->count('student_id');
            
        $paymentRate = $totalStudents > 0 ? round(($studentsWithPayments / $totalStudents) * 100, 2) : 0;
        
        return view('reports.annual', compact(
            'school',
            'currentYear',
            'monthlyStats',
            'annualPayments',
            'totalStudents',
            'studentsWithPayments',
            'paymentRate'
        ));
    }
    
    /**
     * Affiche la répartition des étudiants
     *
     * @return \Illuminate\Http\Response
     */
    public function studentDistribution()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Récupérer les campus de l'école
        $campuses = $school->campuses;
        
        $campusData = [];
        
        foreach ($campuses as $campus) {
            // Récupérer les filières de ce campus
            $fields = \App\Models\Field::where('campus_id', $campus->id)->get();
            
            $fieldData = [];
            $totalCampusStudents = 0;
            
            foreach ($fields as $field) {
                $studentCount = \App\Models\Student::where('field_id', $field->id)->count();
                $totalCampusStudents += $studentCount;
                
                $fieldData[] = [
                    'name' => $field->name,
                    'studentCount' => $studentCount
                ];
            }
            
            // Trier les filières par nombre d'étudiants décroissant
            usort($fieldData, function($a, $b) {
                return $b['studentCount'] - $a['studentCount'];
            });
            
            $campusData[] = [
                'campus' => $campus,
                'fields' => $fieldData,
                'totalStudents' => $totalCampusStudents
            ];
        }
        
        // Trier les campus par nombre total d'étudiants décroissant
        usort($campusData, function($a, $b) {
            return $b['totalStudents'] - $a['totalStudents'];
        });
        
        return view('reports.student-distribution', compact(
            'school',
            'campusData'
        ));
    }

    /**
     * Génère un PDF du rapport de performance des campus
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function performancePdf(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Récupérer tous les campus de l'école
        $campuses = $school->campuses;
        
        // Données pour chaque campus
        $campusData = collect();
        
        foreach ($campuses as $campus) {
            $fields = $campus->fields;
            $fieldIds = $fields->pluck('id')->toArray();
            $students = Student::whereIn('field_id', $fieldIds)->get();
            $studentIds = $students->pluck('id')->toArray();
            
            // Calculer les frais totaux attendus et les montants payés
            $totalFees = $fields->reduce(function ($carry, $field) {
                return $carry + ($field->fees * $field->students()->count());
            }, 0);
            
            $totalPaid = Payment::whereIn('student_id', $studentIds)->sum('amount');
            
            // Détails des filières pour ce campus
            $fieldDetails = [];
            foreach ($fields as $field) {
                $fieldStudents = $field->students;
                $fieldStudentIds = $fieldStudents->pluck('id')->toArray();
                $fieldPaid = Payment::whereIn('student_id', $fieldStudentIds)->sum('amount');
                $fieldFees = $field->fees * $fieldStudents->count();
                
                $fieldDetails[] = [
                    'name' => $field->name,
                    'students' => $fieldStudents->count(),
                    'fees' => $fieldFees,
                    'paid' => $fieldPaid
                ];
            }
            
            // Ajouter les données de campus
            $campusData->push([
                'campus' => $campus,
                'studentCount' => $students->count(),
                'totalFees' => $totalFees,
                'totalPaid' => $totalPaid,
                'recoveryRate' => $totalFees > 0 ? round(($totalPaid / $totalFees) * 100, 1) : 0,
                'fields' => $fieldDetails
            ]);
        }
        
        // Tri par taux de recouvrement (du plus élevé au plus bas)
        $campusData = $campusData->sortByDesc('recoveryRate')->values();
        
        // Génération du PDF avec DomPDF
        $pdf = Pdf::loadView('reports.pdf.performance', compact('campusData', 'school'));
        
        return $pdf->download('rapport-performance-campus-' . date('Y-m-d') . '.pdf');
    }
} 