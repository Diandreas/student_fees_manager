<?php
namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Campus;
use App\Models\EducationLevel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour voir les filières.');
        }
        
        $query = Field::with('campus')->where('school_id', $school->id);
        
        // Recherche par nom ou campus
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('campus', function($q) use ($searchTerm) {
                      $q->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        $fields = $query->paginate(10);
        
        // Conserver les paramètres de recherche dans la pagination
        if ($request->has('search')) {
            $fields->appends(['search' => $request->search]);
        }
        
        return view('fields.index', compact('fields'));
    }

    public function create()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour créer une filière.');
        }

        // Débogage pour vérifier l'école et ses campus
        \Log::info('École sélectionnée:', ['school_id' => $school->id, 'school_name' => $school->name]);
        
        $campuses = Campus::where('school_id', $school->id)->get();
        \Log::info('Campus trouvés:', ['count' => $campuses->count(), 'campus_list' => $campuses->pluck('name')]);
        
        $educationLevels = EducationLevel::where('school_id', $school->id)->orderBy('order')->get();
        
        return view('fields.create', compact('campuses', 'educationLevels'));
    }

    public function store(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour créer une filière.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
            'fees' => 'nullable|numeric|min:0'
        ]);
        
        // Ajouter l'ID de l'école actuelle
        $validated['school_id'] = $school->id;

        Field::create($validated);
        return redirect()->route('fields.index')
            ->with('success', 'Field created successfully');
    }

    public function edit(Field $field)
    {
        $campuses = Campus::all();
        return view('fields.edit', compact('field', 'campuses'));
    }

    public function update(Request $request, Field $field)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
            'fees' => 'nullable|numeric|min:0'
        ]);

        $field->update($validated);
        return redirect()->route('fields.index')
            ->with('success', 'Field updated successfully');
    }

    public function destroy(Field $field)
    {
        $field->delete();
        return redirect()->route('fields.index')
            ->with('success', 'Field deleted successfully');
    }

    /**
     * Affiche les détails d'une filière avec tous ses étudiants
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        // Charger le campus, le niveau d'éducation et les étudiants
        $field->load(['campus', 'educationLevel', 'students']);
        
        // Calculer les statistiques de paiement
        $totalStudents = $field->students->count();
        $totalFees = $totalStudents * $field->fees;
        
        $studentStats = [
            'total' => $totalStudents,
            'paid' => 0,
            'partial' => 0,
            'unpaid' => 0,
            'totalFees' => $totalFees,
            'totalPaid' => 0,
            'remainingAmount' => 0
        ];
        
        // Obtenir des informations sur le paiement pour chaque étudiant
        foreach ($field->students as $student) {
            $student->load('payments');
            $totalPaid = $student->payments->sum('amount');
            $student->paid_amount = $totalPaid;
            $student->remaining_amount = max(0, $field->fees - $totalPaid);
            
            $studentStats['totalPaid'] += $totalPaid;
            
            if ($student->remaining_amount === 0) {
                $studentStats['paid']++;
                $student->payment_status = 'paid';
            } elseif ($totalPaid > 0) {
                $studentStats['partial']++;
                $student->payment_status = 'partial';
            } else {
                $studentStats['unpaid']++;
                $student->payment_status = 'unpaid';
            }
        }
        
        $studentStats['remainingAmount'] = $totalFees - $studentStats['totalPaid'];
        $studentStats['paymentPercentage'] = $totalFees > 0 ? round(($studentStats['totalPaid'] / $totalFees) * 100) : 0;
        
        // Vérifier s'il y a d'autres filières avec le même nom dans ce campus
        $similarFields = Field::where('campus_id', $field->campus_id)
            ->where('name', $field->name)
            ->where('id', '!=', $field->id)
            ->get();
            
        return view('fields.show', compact('field', 'studentStats', 'similarFields'));
    }

    /**
     * Génère un rapport pour une filière spécifique.
     * 
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function report(Field $field)
    {
        $this->authorize('view', $field);
        
        // Charger la filière avec les relations nécessaires
        $field->load(['campus', 'educationLevel', 'students.payments']);
        
        // Calculer les statistiques de paiement
        $totalStudents = $field->students->count();
        $totalFees = $totalStudents * $field->fees;
        $totalPaid = 0;
        
        $paidCount = 0;
        $partialCount = 0;
        $unpaidCount = 0;
        
        foreach ($field->students as $student) {
            $studentPaid = $student->payments->sum('amount');
            $totalPaid += $studentPaid;
            
            if ($studentPaid >= $field->fees) {
                $paidCount++;
            } elseif ($studentPaid > 0) {
                $partialCount++;
            } else {
                $unpaidCount++;
            }
            
            // Ajouter le montant payé comme attribut
            $student->paid_amount = $studentPaid;
            $student->remaining_amount = $field->fees - $studentPaid;
            
            // Définir le statut de paiement
            if ($studentPaid >= $field->fees) {
                $student->payment_status = 'paid';
            } elseif ($studentPaid > 0) {
                $student->payment_status = 'partial';
            } else {
                $student->payment_status = 'unpaid';
            }
        }
        
        $paymentPercentage = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        
        $data = [
            'field' => $field,
            'totalStudents' => $totalStudents,
            'totalFees' => $totalFees,
            'totalPaid' => $totalPaid,
            'paymentPercentage' => $paymentPercentage,
            'paidCount' => $paidCount,
            'partialCount' => $partialCount,
            'unpaidCount' => $unpaidCount,
        ];
        
        // Générer le PDF avec les données
        $pdf = Pdf::loadView('reports.field', $data);
        $fileName = 'rapport_' . $field->name . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
