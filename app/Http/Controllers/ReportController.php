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
} 