<?php
namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;

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

        $campus->delete();
        return redirect()->route('campuses.index')
            ->with('success', 'Campus supprimé avec succès');
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
        
        return view('campuses.show', compact('campus', 'groupedFields', 'school'));
    }
}
