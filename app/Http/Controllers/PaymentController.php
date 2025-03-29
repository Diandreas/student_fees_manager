<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Field;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    // Méthode pour obtenir les informations de paiement d'un étudiant
    public function getStudentPaymentInfo($student_id)
    {
        $student = Student::with(['field', 'payments'])->findOrFail($student_id);
        $totalFees = $student->field->fees;
        $totalPaid = $student->payments->sum('amount');
        $remainingAmount = max(0, $totalFees - $totalPaid);

        return [
            'student' => $student,
            'totalFees' => $totalFees,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount
        ];
    }

    public function store(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour enregistrer un paiement.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Récupérer les étudiants disponibles pour cette école
        $availableStudentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Vérifier que l'étudiant appartient bien à cette école
        if (!in_array($request->student_id, $availableStudentIds)) {
            return redirect()->route('payments.create')
                ->with('error', 'L\'étudiant sélectionné n\'appartient pas à l\'école actuelle.');
        }
        
        $paymentInfo = $this->getStudentPaymentInfo($request->student_id);

        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($availableStudentIds) {
                    if (!in_array($value, $availableStudentIds)) {
                        $fail('L\'étudiant sélectionné n\'appartient pas à l\'école actuelle.');
                    }
                }
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($paymentInfo) {
                    if ($value > $paymentInfo['remainingAmount'] && $paymentInfo['remainingAmount'] > 0) {
                        $fail("Le montant du paiement ($value) ne peut pas dépasser le montant restant à payer ({$paymentInfo['remainingAmount']})");
                    }
                }
            ],
            'description' => 'required|string',
            'payment_date' => 'required|date'
        ]);

        // Génération du numéro de reçu
        $timestamp = now()->format('YmdHis');
        $random = rand(1000, 9999);
        $schoolPrefix = strtoupper(substr($school->name, 0, 3));
        $receipt_number = "{$schoolPrefix}-{$timestamp}-{$random}";

        $payment = new Payment();
        $payment->student_id = $validated['student_id'];
        $payment->amount = $validated['amount'];
        $payment->description = $validated['description'];
        $payment->payment_date = $validated['payment_date'];
        $payment->receipt_number = $receipt_number;
        $payment->school_id = $school->id;
        $payment->save();

        return redirect()->route('payments.index')
            ->with('success', $school->term('payment', 'Paiement') . ' enregistré avec succès. Numéro de reçu: ' . $receipt_number);
    }

    public function create(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour créer un paiement.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Vérifier si un ID d'étudiant a été spécifié
        $selectedStudentId = $request->query('student_id');
        $selectedStudent = null;
        
        if ($selectedStudentId) {
            // Vérifier que l'étudiant appartient à l'école actuelle
            $selectedStudent = Student::with(['field', 'payments'])
                ->whereIn('field_id', $fieldIds)
                ->findOrFail($selectedStudentId);
            
            $paymentInfo = $this->getStudentPaymentInfo($selectedStudentId);
            $selectedStudent->remainingAmount = $paymentInfo['remainingAmount'];
        }

        // Récupérer uniquement les étudiants de l'école actuelle
        $students = Student::with(['field', 'payments'])
            ->whereIn('field_id', $fieldIds)
            ->get()
            ->map(function ($student) {
                $totalFees = $student->field->fees;
                $totalPaid = $student->payments->sum('amount');
                $remainingAmount = max(0, $totalFees - $totalPaid);

                $student->remainingAmount = $remainingAmount;
                return $student;
            });

        return view('payments.create', compact('students', 'selectedStudent', 'school'));
    }

    // Endpoint AJAX pour obtenir les informations de paiement d'un étudiant
    public function getStudentRemainingAmount($student_id)
    {
        $paymentInfo = $this->getStudentPaymentInfo($student_id);
        return response()->json($paymentInfo);
    }

    /**
     * Méthode publique pour obtenir les informations complètes de paiement d'un étudiant
     * 
     * @param int $student_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentPaymentInfoApi($student_id)
    {
        $paymentInfo = $this->getStudentPaymentInfo($student_id);
        
        // Ajouter des informations supplémentaires pour l'API
        $student = $paymentInfo['student'];
        $totalFees = $paymentInfo['totalFees'];
        $totalPaid = $paymentInfo['totalPaid'];
        $remainingAmount = $paymentInfo['remainingAmount'];
        
        // Calcul du pourcentage payé
        $paymentPercentage = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        
        // Déterminer le statut de paiement
        $school = session('current_school');
        $paymentStatus = '';
        $statusColor = '';
        
        if ($remainingAmount == 0) {
            $paymentStatus = $school ? $school->term('fully_paid', 'Payé intégralement') : 'Payé intégralement';
            $statusCode = 'fully_paid';
            $statusColor = 'success';
        } elseif ($totalPaid > 0) {
            $paymentStatus = $school ? $school->term('partially_paid', 'Partiellement payé') : 'Partiellement payé';
            $statusCode = 'partially_paid';
            $statusColor = 'warning';
        } else {
            $paymentStatus = $school ? $school->term('no_payment', 'Aucun paiement') : 'Aucun paiement';
            $statusCode = 'no_payment';
            $statusColor = 'danger';
        }
        
        // Récupérer l'historique des paiements
        $payments = $student->payments()->orderBy('payment_date', 'desc')->get();
        
        return response()->json([
            'student' => $student,
            'field' => $student->field,
            'campus' => $student->field->campus,
            'totalFees' => $totalFees,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount,
            'paymentPercentage' => $paymentPercentage,
            'paymentStatus' => $paymentStatus,
            'statusCode' => $statusCode,
            'statusColor' => $statusColor,
            'payments' => $payments
        ]);
    }

    public function index()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }

        // Filtrer directement par l'école actuelle
        $payments = Payment::with(['student.field.campus'])
                        ->where('school_id', $school->id)
                        ->latest('payment_date')
                        ->get();

        $totalAmount = $payments->sum('amount');
        
        // Récupérer tous les étudiants de l'école actuelle
        $students = Student::where('school_id', $school->id)->get();
        
        $studentTotals = [];
        foreach ($students as $student) {
            $studentPayments = $payments->where('student_id', $student->id);
            $studentTotals[$student->id] = $studentPayments->sum('amount');
        }

        return view('payments.index', [
            'payments' => $payments,
            'totalAmount' => $totalAmount,
            'studentTotals' => $studentTotals,
            'students' => $students
        ]);
    }

    /**
     * Supprime un paiement de la base de données
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        // Empêcher la suppression des paiements
        return redirect()->route('payments.index')
            ->with('error', 'Les paiements ne peuvent pas être supprimés. Cette action a été désactivée pour garantir l\'intégrité des données financières.');
    }

    /**
     * Affiche un reçu de paiement imprimable avec le logo de l'école
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function printReceipt(Payment $payment)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        // Vérifier que le paiement appartient à un étudiant de l'école actuelle
        if ($payment->student->school_id != $school->id) {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement n\'appartient pas à l\'école actuelle');
        }
        
        // Charger les relations nécessaires pour l'affichage du reçu
        $payment->load('student.field.campus');
        
        // Générer le reçu avec une vue spécifique
        return view('payments.receipt', [
            'payment' => $payment,
            'school' => $school
        ]);
    }

    /**
     * Exporte la liste des paiements au format Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel($studentId = null)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        $filename = 'paiements_';
        
        if ($studentId) {
            $student = Student::findOrFail($studentId);
            // Vérifier que l'étudiant appartient à l'école actuelle
            if ($student->field->campus->school_id != $school->id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à cet étudiant');
            }
            
            $paymentInfo = $this->getStudentPaymentInfo($studentId);
            $remainingAmount = $paymentInfo['remainingAmount'];
            
            $filename .= $student->fullName . '_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PaymentsExport($studentId, null, $remainingAmount), $filename);
        } else {
            // Récupérer tous les IDs des étudiants de l'école actuelle via la relation filière -> campus -> école
            $campusIds = $school->campuses()->pluck('id')->toArray();
            $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
            $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
            
            // Préparer les informations sur le reste à payer pour chaque étudiant
            $remainingAmounts = [];
            foreach ($studentIds as $stId) {
                $paymentInfo = $this->getStudentPaymentInfo($stId);
                $remainingAmounts[$stId] = $paymentInfo['remainingAmount'];
            }
            
            $filename .= 'tous_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PaymentsExport(null, $studentIds, $remainingAmounts), $filename);
        }
    }
    
    /**
     * Affiche une version imprimable de la liste des paiements
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printList(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        $studentId = $request->query('student_id');
        
        if ($studentId) {
            // Trouver l'étudiant et vérifier qu'il appartient à l'école actuelle
            $student = Student::with('field.campus')->findOrFail($studentId);
            
            if ($student->school_id != $school->id) {
                return redirect()->route('payments.index')
                    ->with('error', 'Vous n\'avez pas accès à cet étudiant');
            }
            
            $payments = Payment::where('student_id', $studentId)
                ->with(['student.field.campus'])
                ->latest('payment_date')
                ->get();
                
            $paymentInfo = $this->getStudentPaymentInfo($studentId);
            
            $data = [
                'student' => $student,
                'payments' => $payments,
                'totalFees' => $paymentInfo['totalFees'],
                'totalPaid' => $paymentInfo['totalPaid'],
                'remainingAmount' => $paymentInfo['remainingAmount'],
                'school' => $school
            ];
            
            $pdf = PDF::loadView('payments.print-list', $data);
            return $pdf->download($student->fullName . '_paiements_' . date('Y-m-d') . '.pdf');
        } else {
            // Récupérer tous les paiements de cette école
            $payments = Payment::with(['student.field.campus'])
                ->whereHas('student', function($query) use ($school) {
                    $query->where('school_id', $school->id);
                })
                ->latest('payment_date')
                ->get();
                
            $data = [
                'payments' => $payments,
                'school' => $school
            ];
            
            $pdf = PDF::loadView('payments.print-list', $data);
            return $pdf->download($school->name . '_tous_paiements_' . date('Y-m-d') . '.pdf');
        }
    }
    
    /**
     * Affiche les détails d'un paiement
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        // Vérifier que le paiement appartient à un étudiant de l'école actuelle
        if ($payment->student->field->campus->school_id != $school->id) {
            return redirect()->route('payments.index')
                ->with('error', 'Vous n\'avez pas accès à ce paiement');
        }
        
        $payment->load('student.field.campus');
        return view('payments.show', [
            'payment' => $payment,
            'school' => $school
        ]);
    }

    /**
     * Affiche le rapport des paiements
     * 
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école pour accéder aux rapports.');
        }
        
        // Récupérer les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières associées à ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Récupérer les IDs des étudiants associés à ces filières
        $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Statistiques globales pour cette école
        $totalPayments = Payment::whereIn('student_id', $studentIds)->sum('amount');
        $recentPayments = Payment::whereIn('student_id', $studentIds)
                                ->orderBy('payment_date', 'desc')
                                ->limit(5)
                                ->get();
        
        // Nombre total d'étudiants et paiements
        $studentsCount = count($studentIds);
        $paymentsCount = Payment::whereIn('student_id', $studentIds)->count();
        
        // Nombre d'étudiants ayant effectué au moins un paiement
        $studentsWithPayments = Payment::whereIn('student_id', $studentIds)
                                    ->distinct('student_id')
                                    ->count('student_id');
        
        // Statistiques mensuelles
        $monthlyPayments = Payment::whereIn('student_id', $studentIds)
                                ->selectRaw("SUM(amount) as total, strftime('%m', payment_date) as month, strftime('%Y', payment_date) as year")
                                ->whereRaw("strftime('%Y', payment_date) = ?", [date('Y')])
                                ->groupBy('year', 'month')
                                ->orderBy('year')
                                ->orderBy('month')
                                ->get();
        
        // Statistiques par filière
        $paymentsByField = DB::table('payments')
                        ->join('students', 'payments.student_id', '=', 'students.id')
                        ->join('fields', 'students.field_id', '=', 'fields.id')
                        ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
                        ->where('campuses.school_id', $school->id)
                        ->select('fields.name', DB::raw('SUM(payments.amount) as total'))
                        ->groupBy('fields.name')
                        ->orderBy('total', 'desc')
                        ->get();
        
        // Statistiques par campus
        $paymentsByCampus = DB::table('payments')
                        ->join('students', 'payments.student_id', '=', 'students.id')
                        ->join('fields', 'students.field_id', '=', 'fields.id')
                        ->join('campuses', 'fields.campus_id', '=', 'campuses.id')
                        ->where('campuses.school_id', $school->id)
                        ->select('campuses.name', DB::raw('SUM(payments.amount) as total'))
                        ->groupBy('campuses.name')
                        ->orderBy('total', 'desc')
                        ->get();
                        
        // Récupérer tous les paiements de cette école pour le graphique
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
     * Affiche le formulaire d'édition d'un paiement
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour modifier un paiement.');
        }
        
        // Vérifier que le paiement appartient à l'école actuelle
        if ($payment->school_id != $school->id) {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement n\'appartient pas à l\'école actuelle.');
        }
        
        // Vérifier si le paiement a été effectué il y a plus de 7 jours
        $paymentDate = Carbon::parse($payment->payment_date);
        $daysElapsed = $paymentDate->diffInDays(Carbon::now());
        
        if ($daysElapsed > 7) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement ne peut plus être modifié car il a été effectué il y a plus de 7 jours. Contactez l\'administrateur du système pour toute assistance nécessaire.');
        }
        
        $payment->load('student.field');
        
        // Récupérer les campus de cette école
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Récupérer les filières de ces campuses
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Récupérer uniquement les étudiants de l'école actuelle via la relation filières
        $students = Student::whereIn('field_id', $fieldIds)
            ->with(['field', 'payments'])
            ->get()
            ->map(function ($student) {
                $totalFees = $student->field->fees;
                $totalPaid = $student->payments->sum('amount');
                $remainingAmount = max(0, $totalFees - $totalPaid);

                $student->remainingAmount = $remainingAmount;
                return $student;
            });
        
        return view('payments.edit', [
            'payment' => $payment,
            'students' => $students,
            'school' => $school
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour modifier un paiement.');
        }
        
        // Vérifier que le paiement appartient à l'école actuelle
        if ($payment->school_id != $school->id) {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement n\'appartient pas à l\'école actuelle.');
        }
        
        // Vérifier si le paiement a été effectué il y a plus de 7 jours
        $paymentDate = Carbon::parse($payment->payment_date);
        $daysElapsed = $paymentDate->diffInDays(Carbon::now());
        
        if ($daysElapsed > 7) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement ne peut plus être modifié car il a été effectué il y a plus de 7 jours. Contactez l\'administrateur du système pour toute assistance nécessaire.');
        }
        
        $paymentInfo = $this->getStudentPaymentInfo($payment->student_id);
        $currentAmount = $payment->amount;
        
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($paymentInfo, $currentAmount) {
                    // Calculer le montant restant à payer en excluant le paiement actuel
                    $adjustedRemainingAmount = $paymentInfo['remainingAmount'] + $currentAmount;
                    
                    if ($value > $adjustedRemainingAmount && $adjustedRemainingAmount > 0) {
                        $fail("Le montant du paiement ($value) ne peut pas dépasser le montant restant à payer ({$adjustedRemainingAmount})");
                    }
                }
            ],
            'description' => 'required|string',
            'payment_date' => 'required|date'
        ]);
        
        // Historique de modification
        $originalData = $payment->toArray();
        $changes = [
            'amount' => [
                'from' => $payment->amount,
                'to' => $validated['amount']
            ],
            'description' => [
                'from' => $payment->description,
                'to' => $validated['description']
            ],
            'payment_date' => [
                'from' => $payment->payment_date,
                'to' => $validated['payment_date']
            ],
        ];
        
        // Mettre à jour le paiement
        $payment->amount = $validated['amount'];
        $payment->description = $validated['description'];
        $payment->payment_date = $validated['payment_date'];
        $payment->updated_at = now();
        $payment->save();
        
        // Enregistrer l'historique des modifications si nécessaire
        // Vous pourriez avoir une table payment_changes pour suivre ces modifications
        
        return redirect()->route('payments.show', $payment)
            ->with('success', 'Paiement modifié avec succès.');
    }

    /**
     * Exporte la liste des paiements au format PDF pour les rapports
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        // Récupérer les données de paiement de l'école actuelle
        $payments = Payment::with(['student.field.campus'])
            ->whereHas('student.field.campus', function($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->latest('payment_date')
            ->get();
        
        // Statistiques globales
        $totalAmount = $payments->sum('amount');
        $paymentsByMonth = $payments->groupBy(function($payment) {
            return Carbon::parse($payment->payment_date)->format('m-Y');
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount')
            ];
        });
        
        $paymentsByField = $payments->groupBy(function($payment) {
            return $payment->student->field->name;
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount')
            ];
        });
        
        $data = [
            'school' => $school,
            'payments' => $payments,
            'totalAmount' => $totalAmount,
            'paymentsByMonth' => $paymentsByMonth,
            'paymentsByField' => $paymentsByField,
            'generatedAt' => Carbon::now()->format('d/m/Y H:i')
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.payments_pdf', $data);
        
        return $pdf->download($school->name . '_rapport_paiements_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
