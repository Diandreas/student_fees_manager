<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $paymentInfo = $this->getStudentPaymentInfo($request->student_id);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($paymentInfo) {
                    if ($value > $paymentInfo['remainingAmount']) {
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
        $receipt_number = "REC-{$timestamp}-{$random}";

        $payment = new Payment();
        $payment->student_id = $validated['student_id'];
        $payment->amount = $validated['amount'];
        $payment->description = $validated['description'];
        $payment->payment_date = $validated['payment_date'];
        $payment->receipt_number = $receipt_number;
        $payment->save();

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully. Receipt number: ' . $receipt_number);
    }

    public function create()
    {
        $students = Student::with(['field', 'payments'])->get()->map(function ($student) {
            $totalFees = $student->field->fees;
            $totalPaid = $student->payments->sum('amount');
            $remainingAmount = max(0, $totalFees - $totalPaid);

            $student->remainingAmount = $remainingAmount;
            return $student;
        });

        return view('payments.create', compact('students'));
    }

    // Endpoint AJAX pour obtenir les informations de paiement d'un étudiant
    public function getStudentRemainingAmount($student_id)
    {
        $paymentInfo = $this->getStudentPaymentInfo($student_id);
        return response()->json($paymentInfo);
    }

    public function index(Request $request)
    {
        $query = Payment::with(['student.field']);

        // Filtrage par date
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        // Filtrage par étudiant
        if ($request->filled('student')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('full_name', 'LIKE', '%' . $request->student . '%');
            });
        }

        // Filtrage par montant
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Filtrage par filière
        if ($request->filled('field')) {
            $query->whereHas('student.field', function($q) use ($request) {
                $q->where('id', $request->field);
            });
        }

        // Filtrage par campus
        if ($request->filled('campus')) {
            $query->whereHas('student.field.campus', function($q) use ($request) {
                $q->where('id', $request->campus);
            });
        }

        $payments = $query->latest('payment_date')->paginate(10);
        $fields = \App\Models\Field::all();
        $campuses = \App\Models\Campus::all();

        return view('payments.index', compact('payments', 'fields', 'campuses'));
    }

    public function destroy(Payment $payment)
    {
        try {
            $receipt_number = $payment->receipt_number;
            $payment->delete();

            return redirect()->route('payments.index')
                ->with('success', "Le paiement avec le reçu N° $receipt_number a été supprimé avec succès.");

        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', "Une erreur s'est produite lors de la suppression du paiement.");
        }
    }

    public function printReceipt(Payment $payment)
    {
        // Calcul du montant restant
        $student = $payment->student;
        $totalFees = $student->field->fees;
        $totalPaid = $student->payments->sum('amount');
        $remainingAmount = max(0, $totalFees - $totalPaid);

        return view('payments.receipt', compact('payment', 'remainingAmount'));
    }
}
