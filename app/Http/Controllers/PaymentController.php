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
    public function index()
    {
        $payments = Payment::with(['student.field'])
            ->latest('payment_date')
            ->paginate(10);
        return view('payments.index', compact('payments'));
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
        return view('payments.receipt', compact('payment'));
    }
}
