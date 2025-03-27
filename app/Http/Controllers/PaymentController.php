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
    private function getStudentPaymentInfo($student_id)
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
        
        // Vérifier que le paiement appartient à un étudiant de l'école actuelle
        if ($payment->student->school_id != $school->id) {
            return redirect()->route('payments.index')
                ->with('error', 'Vous n\'avez pas accès à ce paiement');
        }
        
        try {
            $receipt_number = $payment->receipt_number;
            $payment->delete();

            return redirect()->route('payments.index')
                ->with('success', $school->term('payment_deleted', 'Le paiement') . " avec le reçu N° $receipt_number a été supprimé avec succès.");

        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', "Une erreur s'est produite lors de la suppression du paiement.");
        }
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
            $filename .= $student->fullName . '_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PaymentsExport($studentId), $filename);
        } else {
            // Récupérer tous les IDs des étudiants de l'école actuelle via la relation filière -> campus -> école
            $campusIds = $school->campuses()->pluck('id')->toArray();
            $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
            $studentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
            
            $filename .= 'tous_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PaymentsExport(null, $studentIds), $filename);
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
            
            return view('payments.print-list', [
                'student' => $student,
                'payments' => $payments,
                'totalFees' => $paymentInfo['totalFees'],
                'totalPaid' => $paymentInfo['totalPaid'],
                'remainingAmount' => $paymentInfo['remainingAmount'],
                'school' => $school
            ]);
        } else {
            // Récupérer tous les paiements de cette école
            $payments = Payment::with(['student.field.campus'])
                ->whereHas('student', function($query) use ($school) {
                    $query->where('school_id', $school->id);
                })
                ->latest('payment_date')
                ->get();
                
            return view('payments.print-list', [
                'payments' => $payments,
                'school' => $school
            ]);
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
        $totalRevenue = Payment::whereIn('student_id', $studentIds)->sum('amount');
        $recentPayments = Payment::whereIn('student_id', $studentIds)
                                ->orderBy('payment_date', 'desc')
                                ->limit(5)
                                ->get();
        
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
            'totalRevenue',
            'recentPayments',
            'monthlyPayments',
            'paymentsByField',
            'paymentsByCampus',
            'allPayments',
            'school'
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
            return redirect()->route('schools.select')->with('error', 'Veuillez sélectionner une école');
        }
        
        // Vérifier que le paiement appartient à un étudiant de l'école actuelle
        if ($payment->student->field->campus->school_id != $school->id) {
            return redirect()->route('payments.index')
                ->with('error', 'Vous n\'avez pas accès à ce paiement');
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
     * Met à jour un paiement dans la base de données
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
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
        
        // Récupérer les campus de cette école
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Récupérer les filières de ces campuses
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Récupérer les IDs des étudiants disponibles pour cette école
        $availableStudentIds = Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Vérifier que l'étudiant sélectionné appartient à cette école
        if (!in_array($request->student_id, $availableStudentIds)) {
            return redirect()->route('payments.edit', $payment)
                ->with('error', 'L\'étudiant sélectionné n\'appartient pas à l\'école actuelle');
        }
        
        $paymentInfo = $this->getStudentPaymentInfo($request->student_id);
        
        // Si on change l'étudiant, on ne doit pas tenir compte du paiement actuel
        $currentPaymentAmount = ($payment->student_id == $request->student_id) ? $payment->amount : 0;
        $actualRemainingAmount = $paymentInfo['remainingAmount'] + $currentPaymentAmount;

        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($availableStudentIds) {
                    if (!in_array($value, $availableStudentIds)) {
                        $fail('L\'étudiant sélectionné n\'appartient pas à l\'école actuelle');
                    }
                }
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($actualRemainingAmount, $paymentInfo) {
                    if ($value > $actualRemainingAmount && $paymentInfo['remainingAmount'] > 0) {
                        $fail("Le montant du paiement ($value) ne peut pas dépasser le montant restant à payer ($actualRemainingAmount)");
                    }
                }
            ],
            'description' => 'required|string',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $payment->student_id = $validated['student_id'];
        $payment->amount = $validated['amount'];
        $payment->description = $validated['description'];
        $payment->payment_date = $validated['payment_date'];
        $payment->payment_method = $request->payment_method;
        $payment->notes = $request->notes;
        $payment->save();

        return redirect()->route('payments.index')
            ->with('success', $school->term('payment', 'Paiement') . ' mis à jour avec succès.');
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
