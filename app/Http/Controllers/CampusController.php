<?php
namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Field;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux campus.');
        }

        $query = Campus::where('school_id', $school->id)->withCount('fields');
        
        // Recherche par nom ou description
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        $campuses = $query->paginate(10);
        
        // Conserver les paramètres de recherche dans la pagination
        if ($request->has('search')) {
            $campuses->appends(['search' => $request->search]);
        }
        
        return view('campuses.index', compact('campuses', 'school'));
    }

    public function create()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour créer un campus.');
        }

        return view('campuses.create', compact('school'));
    }

    public function store(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour créer un campus.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $validated['school_id'] = $school->id;

        Campus::create($validated);
        return redirect()->route('campuses.index')
            ->with('success', 'Campus créé avec succès');
    }

    public function edit(Campus $campus)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour modifier un campus.');
        }

        // Vérifier que le campus appartient à l'école actuelle
        if ($campus->school_id !== $school->id) {
            return redirect()->route('campuses.index')
                ->with('error', 'Vous n\'avez pas accès à ce campus.');
        }

        return view('campuses.edit', compact('campus', 'school'));
    }

    public function update(Request $request, Campus $campus)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour modifier un campus.');
        }

        // Vérifier que le campus appartient à l'école actuelle
        if ($campus->school_id !== $school->id) {
            return redirect()->route('campuses.index')
                ->with('error', 'Vous n\'avez pas accès à ce campus.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $campus->update($validated);
        return redirect()->route('campuses.index')
            ->with('success', 'Campus modifié avec succès');
    }

    public function destroy(Campus $campus)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour supprimer un campus.');
        }

        // Vérifier que le campus appartient à l'école actuelle
        if ($campus->school_id !== $school->id) {
            return redirect()->route('campuses.index')
                ->with('error', 'Vous n\'avez pas accès à ce campus.');
        }
        
        // Vérifier si le campus a des filières
        if ($campus->fields()->count() > 0) {
            $campusTerm = $school->term('campus', 'Campus');
            $fieldTerm = $school->term('fields', 'Filières');
            return redirect()->route('campuses.index')
                ->with('error', 'Impossible de supprimer ce ' . $campusTerm . ' car il contient des ' . $fieldTerm . '. Vous devez d\'abord supprimer toutes les ' . $fieldTerm . ' associées.');
        }

        $campus->delete();
        $campusTerm = $school->term('campus', 'Campus');
        return redirect()->route('campuses.index')
            ->with('success', $campusTerm . ' supprimé avec succès');
    }

    /**
     * Affiche les détails d'un campus avec toutes ses filières
     *
     * @param  \App\Models\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function show(Campus $campus)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour voir les détails d\'un campus.');
        }

        // Vérifier que le campus appartient à l'école actuelle
        if ($campus->school_id !== $school->id) {
            return redirect()->route('campuses.index')
                ->with('error', 'Vous n\'avez pas accès à ce campus.');
        }

        // Charger les filières avec le comptage d'étudiants pour chacune
        $campus->load(['fields' => function($query) {
            $query->withCount('students');
        }]);
        
        // Regrouper les filières par type/niveau pour gérer les doublons
        $groupedFields = $campus->fields->groupBy('name');
        
        // Calcul des statistiques supplémentaires
        $totalFields = $campus->fields->count();
        $totalStudents = $campus->fields->sum('students_count');
        
        // Statistiques de paiement
        $fieldIds = $campus->fields->pluck('id')->toArray();
        
        // Total des frais attendus
        $totalExpectedFees = 0;
        foreach ($campus->fields as $field) {
            $totalExpectedFees += $field->fees * $field->students_count;
        }
        
        // Total des paiements reçus
        $totalPayments = \App\Models\Payment::whereHas('student', function($query) use ($fieldIds) {
                $query->whereIn('field_id', $fieldIds);
            })->sum('amount');
        
        // Reste à payer
        $outstandingFees = max(0, $totalExpectedFees - $totalPayments);
        
        // Taux de recouvrement
        $recoveryRate = $totalExpectedFees > 0 ? round(($totalPayments / $totalExpectedFees) * 100, 2) : 0;
        
        // Statistiques des étudiants par statut de paiement
        $studentsPaymentStatus = $this->getStudentPaymentStatus($fieldIds);
        
        // Top 3 des filières par nombre d'étudiants
        $topFields = $campus->fields->sortByDesc('students_count')->take(3);
        
        // Calculer les statistiques par niveau d'éducation si applicable
        $educationLevelStats = $this->getEducationLevelStats($fieldIds);
        
        return view('campuses.show', compact(
            'campus', 
            'groupedFields', 
            'school',
            'totalFields',
            'totalStudents',
            'totalExpectedFees',
            'totalPayments',
            'outstandingFees',
            'recoveryRate',
            'studentsPaymentStatus',
            'topFields',
            'educationLevelStats'
        ));
    }
    
    /**
     * Get student payment status statistics for the given field IDs
     */
    private function getStudentPaymentStatus($fieldIds)
    {
        $students = Student::whereIn('field_id', $fieldIds)->get();
        
        $fullyPaid = 0;
        $partialPaid = 0;
        $noPaid = 0;
        
        foreach ($students as $student) {
            $field = Field::find($student->field_id);
            $totalFees = $field ? $field->fees : 0;
            
            $paidAmount = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
            
            if ($paidAmount >= $totalFees) {
                $fullyPaid++;
            } elseif ($paidAmount > 0) {
                $partialPaid++;
            } else {
                $noPaid++;
            }
        }
        
        return [
            'fully_paid' => $fullyPaid,
            'partial_paid' => $partialPaid,
            'no_payment' => $noPaid
        ];
    }

    /**
     * Get education level statistics for the given field IDs
     */
    private function getEducationLevelStats($fieldIds)
    {
        $fields = Field::whereIn('id', $fieldIds)
            ->with(['educationLevel', 'students'])
            ->get();
        
        $levelStats = collect();
        $levelsArray = [];
        
        foreach ($fields as $field) {
            $levelName = $field->educationLevel ? $field->educationLevel->name : 'Non défini';
            $studentsCount = $field->students->count();
            
            if (!isset($levelsArray[$levelName])) {
                $levelsArray[$levelName] = 0;
            }
            
            $levelsArray[$levelName] += $studentsCount;
        }
        
        foreach ($levelsArray as $name => $count) {
            $levelStats->push([
                'name' => $name,
                'count' => $count
            ]);
        }
        
        return $levelStats->sortByDesc('count')->values();
    }

    /**
     * Génère un document PDF listant les étudiants solvables (qui ont intégralement payé) pour toutes les filières d'un campus
     * 
     * @param  \App\Models\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function downloadSolvableStudents(Campus $campus)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour télécharger la liste.');
        }
        
        // Vérifier que le campus appartient à l'école actuelle
        if ($campus->school_id !== $school->id) {
            return redirect()->route('campuses.index')
                ->with('error', 'Vous n\'avez pas accès à ce campus.');
        }
        
        // Charger les filières avec leurs étudiants
        $campus->load('fields.students.payments');
        
        // Collecter tous les étudiants solvables
        $solvableStudents = collect();
        
        foreach ($campus->fields as $field) {
            // Filtrer les étudiants solvables (ceux qui ont intégralement payé)
            $fieldSolvableStudents = $field->students->filter(function($student) use ($field) {
                $totalPaid = $student->payments->sum('amount');
                $student->paid_amount = $totalPaid;
                $student->remaining_amount = max(0, $field->fees - $totalPaid);
                $student->field_name = $field->name; // Ajouter le nom de la filière
                
                // Un étudiant est solvable s'il a payé au moins le montant des frais
                return $totalPaid >= $field->fees;
            });
            
            $solvableStudents = $solvableStudents->concat($fieldSolvableStudents);
        }
        
        $data = [
            'campus' => $campus,
            'students' => $solvableStudents,
            'title' => 'Étudiants solvables du campus ' . $campus->name,
            'description' => 'Liste des étudiants ayant intégralement payé leurs frais dans toutes les filières du campus',
            'currentSchool' => $school
        ];
        
        // Générer le PDF avec les données
        $pdf = Pdf::loadView('reports.campus_students_list', $data);
        $fileName = 'etudiants_solvables_campus_' . $campus->name . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($fileName);
    }

    /**
     * Génère un document PDF listant les étudiants insolvables (qui n'ont pas intégralement payé) pour toutes les filières d'un campus
     * 
     * @param  \App\Models\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function downloadInsolvableStudents(Campus $campus)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour télécharger la liste.');
        }
        
        // Vérifier que le campus appartient à l'école actuelle
        if ($campus->school_id !== $school->id) {
            return redirect()->route('campuses.index')
                ->with('error', 'Vous n\'avez pas accès à ce campus.');
        }
        
        // Charger les filières avec leurs étudiants
        $campus->load('fields.students.payments');
        
        // Collecter tous les étudiants insolvables
        $insolvableStudents = collect();
        
        foreach ($campus->fields as $field) {
            // Filtrer les étudiants insolvables (ceux qui n'ont pas intégralement payé)
            $fieldInsolvableStudents = $field->students->filter(function($student) use ($field) {
                $totalPaid = $student->payments->sum('amount');
                $student->paid_amount = $totalPaid;
                $student->remaining_amount = max(0, $field->fees - $totalPaid);
                $student->field_name = $field->name; // Ajouter le nom de la filière
                
                // Un étudiant est insolvable s'il n'a pas payé l'intégralité des frais
                return $totalPaid < $field->fees;
            });
            
            $insolvableStudents = $insolvableStudents->concat($fieldInsolvableStudents);
        }
        
        $data = [
            'campus' => $campus,
            'students' => $insolvableStudents,
            'title' => 'Étudiants insolvables du campus ' . $campus->name,
            'description' => 'Liste des étudiants n\'ayant pas intégralement payé leurs frais dans toutes les filières du campus',
            'currentSchool' => $school
        ];
        
        // Générer le PDF avec les données
        $pdf = Pdf::loadView('reports.campus_students_list', $data);
        $fileName = 'etudiants_insolvables_campus_' . $campus->name . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
