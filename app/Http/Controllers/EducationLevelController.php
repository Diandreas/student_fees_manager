<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationLevelController extends Controller
{
    /**
     * Affiche la liste des niveaux d'éducation.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $school = Auth::user()->currentSchool;
        $educationLevels = EducationLevel::where('school_id', $school->id)->orderBy('name')->get();
        
        return view('education-levels.index', compact('educationLevels'));
    }

    /**
     * Affiche le formulaire de création d'un niveau d'éducation.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('education-levels.create');
    }

    /**
     * Enregistre un nouveau niveau d'éducation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $school = Auth::user()->currentSchool;
        
        EducationLevel::create([
            'name' => $request->name,
            'description' => $request->description,
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('education-levels.index')
            ->with('success', 'Niveau d\'éducation créé avec succès.');
    }

    /**
     * Affiche les détails d'un niveau d'éducation.
     *
     * @param  \App\Models\EducationLevel  $educationLevel
     * @return \Illuminate\View\View
     */
    public function show(EducationLevel $educationLevel)
    {
        $this->authorize('view', $educationLevel);
        
        // Charger les filières associées à ce niveau d'éducation
        $fields = $educationLevel->fields()->with('campus')->get();
        
        return view('education-levels.show', compact('educationLevel', 'fields'));
    }

    /**
     * Affiche le formulaire de modification d'un niveau d'éducation.
     *
     * @param  \App\Models\EducationLevel  $educationLevel
     * @return \Illuminate\View\View
     */
    public function edit(EducationLevel $educationLevel)
    {
        $this->authorize('update', $educationLevel);
        
        return view('education-levels.edit', compact('educationLevel'));
    }

    /**
     * Met à jour un niveau d'éducation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EducationLevel  $educationLevel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, EducationLevel $educationLevel)
    {
        $this->authorize('update', $educationLevel);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $educationLevel->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        return redirect()->route('education-levels.index')
            ->with('success', 'Niveau d\'éducation mis à jour avec succès.');
    }

    /**
     * Supprime un niveau d'éducation.
     *
     * @param  \App\Models\EducationLevel  $educationLevel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(EducationLevel $educationLevel)
    {
        $this->authorize('delete', $educationLevel);
        
        // Vérifier si ce niveau d'éducation a des filières associées
        if ($educationLevel->fields()->count() > 0) {
            return redirect()->route('education-levels.index')
                ->with('error', 'Impossible de supprimer ce niveau d\'éducation car il est utilisé par des filières.');
        }
        
        $educationLevel->delete();
        
        return redirect()->route('education-levels.index')
            ->with('success', 'Niveau d\'éducation supprimé avec succès.');
    }
} 