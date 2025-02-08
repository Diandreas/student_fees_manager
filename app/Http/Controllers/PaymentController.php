<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('student')->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::all();
        return view('payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'payment_date' => 'required|date'
        ]);

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully');
    }

    public function printReceipt(Payment $payment)
    {
        $pdf = PDF::loadView('payments.receipt', compact('payment'));
        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}
