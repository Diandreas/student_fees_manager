<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Field;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux étudiants.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        $query = Student::with(['field.campus', 'payments'])
                        ->whereIn('field_id', $fieldIds);
        
        // Recherche par nom, email, téléphone ou filière
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('fullName', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('field', function($q) use ($searchTerm) {
                      $q->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        $students = $query->paginate(10);
        
        // Conserver les paramètres de recherche dans la pagination
        if ($request->has('search')) {
            $students->appends(['search' => $request->search]);
        }
        
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour créer un étudiant.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fields = Field::with('campus')
                    ->whereIn('campus_id', $campusIds)
                    ->get();
                    
        if ($fields->isEmpty()) {
            return redirect()->route('fields.create')
                ->with('info', 'Veuillez d\'abord créer une filière avant d\'ajouter des étudiants.');
        }
        
        return view('students.create', compact('fields', 'school'));
    }

    public function store(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour créer un étudiant.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        $validated = $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email|unique:students',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:45',
            'field_id' => 'required|exists:fields,id|in:' . implode(',', $fieldIds),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Informations des parents
            'parent_name' => 'nullable|string|max:255',
            'parent_tel' => 'nullable|string|max:45',
            'parent_email' => 'nullable|email|max:255',
            'parent_profession' => 'nullable|string|max:255',
            'parent_address' => 'nullable|string',
            
            // Contact d'urgence
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_tel' => 'nullable|string|max:45',
            'relationship' => 'nullable|string|max:100',
        ]);

        // Traitement de la photo si elle est fournie
        $photoFileName = null;
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $photoFileName = 'student_' . time() . '_' . Str::random(10) . '.' . $photoFile->getClientOriginalExtension();
            $photoFile->storeAs('public/students', $photoFileName);
        }

        // Créer l'enregistrement de l'étudiant
        $student = new Student([
            'fullName' => $validated['fullName'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'phone' => $validated['phone'] ?? null,
            'field_id' => $validated['field_id'],
            'photo' => $photoFileName,
            
            // Informations des parents
            'parent_name' => $validated['parent_name'] ?? null,
            'parent_tel' => $validated['parent_tel'] ?? null,
            'parent_email' => $validated['parent_email'] ?? null,
            'parent_profession' => $validated['parent_profession'] ?? null,
            'parent_address' => $validated['parent_address'] ?? null,
            
            // Contact d'urgence
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_tel' => $validated['emergency_contact_tel'] ?? null,
            'relationship' => $validated['relationship'] ?? null,
        ]);

        $student->user_id = Auth::id();
        $student->save();

        $studentTerm = $school->term('student', 'Étudiant');
        
        return redirect()->route('students.index')
            ->with('success', $studentTerm . ' enregistré avec succès');
    }

    /**
     * Affiche les détails d'un étudiant
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour voir les détails de l\'étudiant.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Vérifier que l'étudiant appartient à l'école actuelle
        if (!in_array($student->field_id, $fieldIds)) {
            return redirect()->route('students.index')
                ->with('error', 'Cet étudiant n\'appartient pas à l\'école actuelle.');
        }
        
        // Charger les relations nécessaires
        $student->load(['field.campus', 'payments' => function($query) {
            $query->latest('payment_date');
        }]);
        
        // Calculer les montants
        $totalFees = $student->field->fees;
        $totalPaid = $student->payments->sum('amount');
        $remainingAmount = max(0, $totalFees - $totalPaid);
        
        // Déterminer le statut de paiement
        $paymentStatus = '';
        $statusColor = '';
        
        if ($remainingAmount == 0) {
            $paymentStatus = $school->term('fully_paid', 'Payé intégralement');
            $statusColor = 'success';
        } elseif ($totalPaid > 0) {
            $paymentStatus = $school->term('partially_paid', 'Partiellement payé');
            $statusColor = 'warning';
        } else {
            $paymentStatus = $school->term('no_payment', 'Aucun paiement');
            $statusColor = 'danger';
        }
        
        // Pourcentage de paiement
        $paymentPercentage = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        
        return view('students.show', compact(
            'student', 
            'totalFees', 
            'totalPaid', 
            'remainingAmount', 
            'paymentStatus', 
            'statusColor',
            'paymentPercentage',
            'school'
        ));
    }

    public function edit(Student $student)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour modifier un étudiant.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Vérifier que l'étudiant appartient à l'école actuelle
        if (!in_array($student->field_id, $fieldIds)) {
            return redirect()->route('students.index')
                ->with('error', 'Cet étudiant n\'appartient pas à l\'école actuelle.');
        }
        
        $fields = Field::with('campus')
                ->whereIn('campus_id', $campusIds)
                ->get();
                
        return view('students.edit', compact('student', 'fields', 'school'));
    }

    public function update(Request $request, Student $student)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour modifier un étudiant.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Vérifier que l'étudiant appartient à l'école actuelle
        if (!in_array($student->field_id, $fieldIds)) {
            return redirect()->route('students.index')
                ->with('error', 'Cet étudiant n\'appartient pas à l\'école actuelle.');
        }
        
        $validated = $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'address' => 'required|string',
            'phone' => 'nullable|string|max:45',
            'field_id' => [
                'required',
                'exists:fields,id',
                // S'assurer que la filière sélectionnée appartient à cette école
                function ($attribute, $value, $fail) use ($fieldIds) {
                    if (!in_array($value, $fieldIds)) {
                        $fail('La filière sélectionnée n\'appartient pas à l\'école actuelle.');
                    }
                }
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Informations des parents
            'parent_name' => 'nullable|string|max:255',
            'parent_tel' => 'nullable|string|max:45',
            'parent_email' => 'nullable|email|max:255',
            'parent_profession' => 'nullable|string|max:255',
            'parent_address' => 'nullable|string',
            
            // Contact d'urgence
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_tel' => 'nullable|string|max:45',
            'relationship' => 'nullable|string|max:100',
        ]);

        // Traitement de la photo si elle est fournie
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($student->photo) {
                Storage::delete('public/students/' . $student->photo);
            }
            
            // Enregistrer la nouvelle photo
            $photoFile = $request->file('photo');
            $photoFileName = 'student_' . time() . '_' . Str::random(10) . '.' . $photoFile->getClientOriginalExtension();
            $photoFile->storeAs('public/students', $photoFileName);
            
            // Mettre à jour le nom du fichier photo
            $validated['photo'] = $photoFileName;
        }

        $student->update($validated);
        
        $studentTerm = $school->term('student', 'Étudiant');
        
        return redirect()->route('students.index')
            ->with('success', 'Informations de l\''. strtolower($studentTerm) . ' mises à jour avec succès');
    }

    public function destroy(Student $student)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez sélectionner une école pour supprimer un étudiant.');
        }
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Vérifier que l'étudiant appartient à l'école actuelle
        if (!in_array($student->field_id, $fieldIds)) {
            return redirect()->route('students.index')
                ->with('error', 'Cet étudiant n\'appartient pas à l\'école actuelle.');
        }
        
        // Supprimer la photo de l'étudiant si elle existe
        if ($student->photo) {
            Storage::delete('public/students/' . $student->photo);
        }
        
        // Supprimer l'utilisateur (et l'étudiant en cascade)
        if ($student->user) {
            $student->user->delete();
        } else {
            $student->delete();
        }
        
        $studentTerm = $school->term('student', 'Étudiant');
        
        return redirect()->route('students.index')
            ->with('success', $studentTerm . ' supprimé avec succès');
    }

    /**
     * Affiche le rapport global des étudiants
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.index')
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
        $paymentStats = Student::with(['field', 'payments'])
            ->whereIn('field_id', $fieldIds)
            ->get()
            ->reduce(function ($stats, $student) {
                $totalFees = $student->field->fees;
                $totalPaid = $student->payments->sum('amount');
                $remainingAmount = max(0, $totalFees - $totalPaid);
                
                if ($totalPaid >= $totalFees) {
                    $stats['fullyPaid']++;
                } elseif ($totalPaid > 0) {
                    $stats['partiallyPaid']++;
                } else {
                    $stats['notPaid']++;
                }
                
                $stats['totalFees'] += $totalFees;
                $stats['totalPaid'] += $totalPaid;
                $stats['totalRemaining'] += $remainingAmount;
                
                return $stats;
            }, [
                'fullyPaid' => 0,
                'partiallyPaid' => 0,
                'notPaid' => 0,
                'totalFees' => 0,
                'totalPaid' => 0,
                'totalRemaining' => 0
            ]);
            
        // Calcul du taux de recouvrement
        $recoveryRate = $paymentStats['totalFees'] > 0 
            ? round(($paymentStats['totalPaid'] / $paymentStats['totalFees']) * 100, 2)
            : 0;
            
        // Étudiants par filière de cette école
        $studentsByField = Field::select('fields.*')
            ->whereIn('fields.id', $fieldIds)
            ->join('students', 'fields.id', '=', 'students.field_id')
            ->groupBy('fields.id')
            ->select('fields.*', DB::raw('COUNT(students.id) as students_count'))
            ->orderByDesc('students_count')
            ->get();
            
        // Répartition par campus de cette école
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
}
