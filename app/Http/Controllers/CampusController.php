<?php
namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $query = Campus::withCount('fields');
        
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
        
        return view('campuses.index', compact('campuses'));
    }

    public function create()
    {
        return view('campuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Campus::create($validated);
        return redirect()->route('campuses.index')
            ->with('success', 'Campus created successfully');
    }

    public function edit(Campus $campus)
    {
        return view('campuses.edit', compact('campus'));
    }

    public function update(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $campus->update($validated);
        return redirect()->route('campuses.index')
            ->with('success', 'Campus updated successfully');
    }

    public function destroy(Campus $campus)
    {
        $campus->delete();
        return redirect()->route('campuses.index')
            ->with('success', 'Campus deleted successfully');
    }

    /**
     * Affiche les détails d'un campus avec toutes ses filières
     *
     * @param  \App\Models\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function show(Campus $campus)
    {
        // Charger les filières avec le comptage d'étudiants pour chacune
        $campus->load(['fields' => function($query) {
            $query->withCount('students');
        }]);
        
        // Regrouper les filières par type/niveau pour gérer les doublons
        $groupedFields = $campus->fields->groupBy('name');
        
        return view('campuses.show', compact('campus', 'groupedFields'));
    }
}
