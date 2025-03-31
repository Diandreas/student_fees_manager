<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuickPaymentController extends Controller
{
    /**
     * Affiche la page des paiements rapides
     */
    public function index()
    {
        return view('payments.quick');
    }

    /**
     * Recherche d'étudiants pour l'autocomplétion
     */
    public function searchStudents(Request $request)
    {
        $query = $request->input('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $students = Student::with('field')
            ->where(function($q) use ($query) {
                $q->where('fullName', 'LIKE', "%{$query}%")
                  ->orWhere('student_id', 'LIKE', "%{$query}%");
            })
            ->where('school_id', Auth::user()->school_id)
            ->take(10)
            ->get();
            
        return response()->json($students);
    }
    
    /**
     * Synchronise les paiements stockés localement
     */
    public function syncOfflinePayments(Request $request)
    {
        $payments = $request->input('payments', []);
        $results = [];
        
        foreach ($payments as $paymentData) {
            try {
                // Vérifier si l'étudiant existe
                $student = Student::find($paymentData['student_id']);
                
                if (!$student) {
                    $results[] = [
                        'success' => false,
                        'id' => $paymentData['id'] ?? null,
                        'message' => 'Étudiant non trouvé'
                    ];
                    continue;
                }
                
                // Créer le paiement
                $payment = new Payment();
                $payment->student_id = $student->id;
                $payment->amount = $paymentData['amount'];
                $payment->payment_date = $paymentData['payment_date'];
                $payment->created_by = Auth::id();
                $payment->school_id = Auth::user()->school_id;
                $payment->receipt_number = 'R-' . date('Ymd') . '-' . Str::random(5);
                
                if (isset($paymentData['description'])) {
                    $payment->description = $paymentData['description'];
                }
                
                if (isset($paymentData['payment_method'])) {
                    $payment->payment_method = $paymentData['payment_method'];
                }
                
                $payment->save();
                
                $results[] = [
                    'success' => true,
                    'id' => $paymentData['id'] ?? null,
                    'receipt_number' => $payment->receipt_number,
                    'message' => 'Paiement enregistré avec succès'
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'success' => false,
                    'id' => $paymentData['id'] ?? null,
                    'message' => 'Erreur: ' . $e->getMessage()
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
} 