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
        $school = session('current_school');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Récupérer les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        $students = Student::with(['field.campus', 'payments'])
            ->whereIn('field_id', $fieldIds)
            ->where(function($q) use ($query) {
                $q->where('fullName', 'LIKE', "%{$query}%")
                  ->orWhere('student_id', 'LIKE', "%{$query}%");
            })
            ->take(15)
            ->get()
            ->map(function ($student) use ($school) {
                // Calcul des informations de paiement
                $totalFees = $student->field->fees;
                $totalPaid = $student->payments->sum('amount');
                $remainingAmount = max(0, $totalFees - $totalPaid);
                $paymentPercentage = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
                
                // Déterminer le statut de paiement
                $paymentStatus = '';
                if ($remainingAmount == 0) {
                    $paymentStatus = 'fully_paid';
                } elseif ($totalPaid > 0) {
                    $paymentStatus = 'partially_paid';
                } else {
                    $paymentStatus = 'no_payment';
                }
                
                // S'assurer que les propriétés sont correctement nommées pour la vue
                $student->full_name = $student->fullName ?? ($student->firstName . ' ' . $student->lastName);
                
                // Information complémentaire
                $student->payment_status = $paymentStatus;
                $student->total_fees = $totalFees;
                $student->total_paid = $totalPaid;
                $student->remaining_amount = $remainingAmount;
                $student->payment_percentage = $paymentPercentage;
                
                return $student;
            });
            
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