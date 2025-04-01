<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Invoice;
use App\Exports\YearEndReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\YearlyStat;
use App\Models\Campus;
use App\Models\Field;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the archives.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux archives.');
        }

        // Filtrer par école actuelle
        $query = Archive::with(['creator'])
                      ->where('school_id', $school->id);
        
        // Recherche par action ou détails
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('details', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('academic_year', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('notes', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtrer par type d'action
        if ($request->has('action_type') && !empty($request->action_type)) {
            $query->where('action', $request->action_type);
        }

        // Filtrer par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $archives = $query->latest()
                       ->paginate(15);

        // Conserver les paramètres de recherche dans la pagination
        if ($request->has('search')) {
            $archives->appends(['search' => $request->search]);
        }
        if ($request->has('action_type')) {
            $archives->appends(['action_type' => $request->action_type]);
        }
        if ($request->has('date_from')) {
            $archives->appends(['date_from' => $request->date_from]);
        }
        if ($request->has('date_to')) {
            $archives->appends(['date_to' => $request->date_to]);
        }

        return view('archives.index', compact('archives', 'school'));
    }

    /**
     * Show the form for generating a new archive.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $school = session('current_school');
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école.');
        }
        
        $currentYear = Carbon::now()->year;

        // Déterminer les années académiques disponibles
        $years = [];
        $startYear = $school->created_at->year;
        
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $academicYear = "{$year}-" . ($year + 1);
            $years[$academicYear] = $academicYear;
        }

        return view('archives.create', compact('years', 'school'));
    }

    /**
     * Generate and store a new archive.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $school = session('current_school');
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école.');
        }
        
        $academicYear = $request->academic_year;

        // Vérifier si une archive existe déjà pour cette année
        $existingArchive = Archive::where('school_id', $school->id)
                                 ->where('academic_year', $academicYear)
                                 ->first();
        
        if ($existingArchive) {
            return redirect()->route('archives.index')
                            ->with('error', "Une archive existe déjà pour l'année académique {$academicYear}.");
        }

        try {
            DB::beginTransaction();

            // Générer le rapport Excel
            $filename = "archive_" . $school->id . "_" . str_replace('-', '_', $academicYear) . "_" . time() . ".xlsx";
            $filePath = "archives/{$school->id}/{$filename}";
            
            Excel::store(new YearEndReportExport($school->id, $academicYear), $filePath, 'public');
            
            // Récupérer les statistiques pour cette année
            $students = Student::where('school_id', $school->id)->get();
            $studentsCount = $students->count();
            
            $totalInvoiced = Invoice::where('school_id', $school->id)->sum('amount');
            $totalPaid = Payment::where('school_id', $school->id)->sum('amount');
            $totalRemaining = $totalInvoiced - $totalPaid;
            
            // Créer l'enregistrement d'archive
            $archive = Archive::create([
                'school_id' => $school->id,
                'academic_year' => $academicYear,
                'file_path' => $filePath,
                'file_name' => $filename,
                'file_size' => Storage::disk('public')->size($filePath),
                'students_count' => $studentsCount,
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'total_remaining' => $totalRemaining,
                'created_by' => Auth::check() ? Auth::id() : null,
                'notes' => $request->notes,
            ]);

            // Générer les statistiques annuelles détaillées
            $this->generateYearlyStats($school, $academicYear, $archive);

            DB::commit();

            return redirect()->route('archives.show', $archive)
                            ->with('success', "L'archive pour l'année académique {$academicYear} a été générée avec succès.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('archives.create')
                            ->with('error', "Une erreur s'est produite lors de la génération de l'archive: " . $e->getMessage());
        }
    }

    /**
     * Display the specified archive.
     *
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function show(Archive $archive)
    {
        // Vérifier que l'utilisateur a accès à cette archive
        $school = session('current_school');
        if (!$school || $archive->school_id !== $school->id) {
            return redirect()->route('schools.select')
                ->with('error', "Vous n'avez pas accès à cette archive.");
        }

        return view('archives.show', compact('archive'));
    }

    /**
     * Download the archive file.
     *
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function download(Archive $archive)
    {
        // Vérifier que l'utilisateur a accès à cette archive
        $school = session('current_school');
        if (!$school || $archive->school_id !== $school->id) {
            return redirect()->route('schools.select')
                ->with('error', "Vous n'avez pas accès à cette archive.");
        }

        return response()->download(storage_path('app/public/' . $archive->file_path), $archive->file_name);
    }

    /**
     * Clean up the database for the specified academic year.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function cleanup(Request $request, Archive $archive)
    {
        // Vérifier la confirmation
        $request->validate([
            'confirmation' => 'required|in:CONFIRMER',
        ], [
            'confirmation.required' => 'Vous devez saisir le mot CONFIRMER pour valider la suppression des données.',
            'confirmation.in' => 'Vous devez saisir exactement le mot CONFIRMER pour valider la suppression des données.',
        ]);

        try {
            DB::beginTransaction();

            $school = session('current_school');
            $academicYear = $archive->academic_year;

            // Supprimer les paiements détaillés tout en conservant les totaux pour les statistiques
            // On garde les informations des étudiants mais on supprime les détails de paiement
            // Note: Dans une vraie application, on pourrait archiver ces données dans une autre table

            // Supprimer les paiements
            $paymentsDeleted = Payment::where('school_id', $school->id)->delete();
            
            // Supprimer les factures
            $invoicesDeleted = Invoice::where('school_id', $school->id)->delete();
            
            DB::commit();
            
            return redirect()->route('archives.show', $archive)
                            ->with('success', "Les données ont été nettoyées avec succès. {$paymentsDeleted} paiements et {$invoicesDeleted} factures ont été supprimés.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('archives.show', $archive)
                            ->with('error', "Une erreur s'est produite lors du nettoyage des données: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified archive from storage.
     *
     * @param  \App\Models\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archive $archive)
    {
        // Vérifier que l'utilisateur a accès à cette archive
        $school = session('current_school');
        if (!$school || $archive->school_id !== $school->id) {
            return redirect()->route('schools.select')
                ->with('error', "Vous n'avez pas accès à cette archive.");
        }

        try {
            // Supprimer le fichier d'archive
            if (Storage::disk('public')->exists($archive->file_path)) {
                Storage::disk('public')->delete($archive->file_path);
            }
            
            // Supprimer l'enregistrement d'archive et les statistiques associées
            $archive->delete();
            
            return redirect()->route('archives.index')
                            ->with('success', "L'archive a été supprimée avec succès.");
        } catch (\Exception $e) {
            return redirect()->route('archives.show', $archive)
                            ->with('error', "Une erreur s'est produite lors de la suppression de l'archive: " . $e->getMessage());
        }
    }

    /**
     * Generate detailed yearly statistics.
     *
     * @param  \App\Models\School  $school
     * @param  string  $academicYear
     * @param  \App\Models\Archive  $archive
     * @return void
     */
    private function generateYearlyStats($school, $academicYear, $archive)
    {
        // Récupérer les années de l'année académique (ex: 2023-2024 => [2023, 2024])
        $years = explode('-', $academicYear);
        $startYear = $years[0];
        $endYear = $years[1] ?? (intval($startYear) + 1);
        
        $startDate = Carbon::createFromDate($startYear, 9, 1); // Généralement, l'année scolaire commence en septembre
        $endDate = Carbon::createFromDate($endYear, 8, 31);    // Et se termine en août

        // Statistiques des étudiants
        $totalStudents = Student::where('school_id', $school->id)->count();
        $newStudents = Student::where('school_id', $school->id)
                             ->whereBetween('created_at', [$startDate, $endDate])
                             ->count();
        // Pour les étudiants diplômés, il faudrait un champ spécifique, ici c'est un exemple
        $graduatedStudents = 0;

        // Statistiques financières
        $totalInvoiced = Invoice::where('school_id', $school->id)
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->sum('amount');
        $totalPaid = Payment::where('school_id', $school->id)
                           ->whereBetween('date', [$startDate, $endDate])
                           ->sum('amount');
        $totalRemaining = $totalInvoiced - $totalPaid;
        $recoveryRate = $totalInvoiced > 0 ? ($totalPaid / $totalInvoiced) * 100 : 0;

        // Statistiques par campus
        $campusStats = [];
        $campuses = Campus::where('school_id', $school->id)->get();
        foreach ($campuses as $campus) {
            $campusStudents = Student::where('school_id', $school->id)
                                    ->where('campus_id', $campus->id)
                                    ->pluck('id')
                                    ->toArray();
            
            $campusPaid = Payment::where('school_id', $school->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->whereIn('student_id', $campusStudents)
                                ->sum('amount');
            
            $campusStats[$campus->name] = $campusPaid;
        }

        // Statistiques par filière
        $fieldStats = [];
        $fields = Field::where('school_id', $school->id)->get();
        foreach ($fields as $field) {
            $fieldStudents = Student::where('school_id', $school->id)
                                   ->where('field_id', $field->id)
                                   ->pluck('id')
                                   ->toArray();
            
            $fieldPaid = Payment::where('school_id', $school->id)
                               ->whereBetween('date', [$startDate, $endDate])
                               ->whereIn('student_id', $fieldStudents)
                               ->sum('amount');
            
            $fieldStats[$field->name] = $fieldPaid;
        }

        // Statistiques mensuelles des paiements
        $monthlyPayments = [];
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate($startYear, $month, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            
            // Ajuster pour l'année académique (sept - août)
            if ($month < 9) {
                $startOfMonth->setYear($endYear);
                $endOfMonth->setYear($endYear);
            } else {
                $startOfMonth->setYear($startYear);
                $endOfMonth->setYear($startYear);
            }
            
            $monthlyAmount = Payment::where('school_id', $school->id)
                                  ->whereBetween('date', [$startOfMonth, $endOfMonth])
                                  ->sum('amount');
            
            $monthlyPayments[$month] = $monthlyAmount;
        }

        // Créer ou mettre à jour les statistiques annuelles
        YearlyStat::updateOrCreate(
            [
                'school_id' => $school->id,
                'academic_year' => $academicYear
            ],
            [
                'total_students' => $totalStudents,
                'new_students' => $newStudents,
                'graduated_students' => $graduatedStudents,
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'total_remaining' => $totalRemaining,
                'recovery_rate' => $recoveryRate,
                'campus_stats' => $campusStats,
                'field_stats' => $fieldStats,
                'monthly_payments' => $monthlyPayments,
                'archive_id' => $archive->id
            ]
        );
    }
} 