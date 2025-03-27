<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SchoolAdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the form for creating a new school admin.
     */
    public function create(School $school)
    {
        // Vérifier si l'utilisateur a le droit d'ajouter des administrateurs
        $this->authorize('manageAdmins', $school);
        
        // Récupérer les utilisateurs qui ne sont pas déjà administrateurs de cette école
        $users = User::whereDoesntHave('schools', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->get();
        
        return view('schools.admins.create', compact('school', 'users'));
    }
    
    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request, School $school)
    {
        // Vérifier si l'utilisateur a le droit d'ajouter des administrateurs
        $this->authorize('manageAdmins', $school);
        
        // Validation de base
        $request->validate([
            'admin_type' => 'required|in:existing,new',
            'role' => 'required|in:admin,manager,finance,secretary',
        ]);
        
        // Traitement selon le type d'ajout
        if ($request->admin_type === 'existing') {
            // Ajouter un utilisateur existant
            $request->validate([
                'user_id' => [
                    'required',
                    'exists:users,id',
                    Rule::unique('school_admins', 'user_id')->where(function ($query) use ($school) {
                        return $query->where('school_id', $school->id);
                    }),
                ],
            ]);
            
            $school->admins()->attach($request->user_id, [
                'role' => $request->role,
                'permissions' => json_encode([]),
            ]);
            
            return redirect()->route('schools.show', $school)
                ->with('success', 'Administrateur ajouté avec succès');
        } else {
            // Créer un nouvel utilisateur
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            $school->admins()->attach($user->id, [
                'role' => $request->role,
                'permissions' => json_encode([]),
            ]);
            
            // Envoi d'un email d'invitation si demandé
            if ($request->has('send_invitation')) {
                // Logique d'envoi d'email à implémenter
            }
            
            return redirect()->route('schools.show', $school)
                ->with('success', 'Nouvel administrateur créé et ajouté avec succès');
        }
    }
    
    /**
     * Show the form for editing a school admin.
     */
    public function edit(School $school, User $admin)
    {
        // Vérifier si l'utilisateur a le droit de modifier des administrateurs
        $this->authorize('manageAdmins', $school);
        
        // Vérifier si l'utilisateur est bien un admin de cette école
        if (!$school->admins()->where('user_id', $admin->id)->exists()) {
            abort(404, 'Cet utilisateur n\'est pas administrateur de cette école');
        }
        
        return view('schools.admins.edit', compact('school', 'admin'));
    }
    
    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, School $school, User $admin)
    {
        // Vérifier si l'utilisateur a le droit de modifier des administrateurs
        $this->authorize('manageAdmins', $school);
        
        // Vérifier si l'utilisateur est bien un admin de cette école
        if (!$school->admins()->where('user_id', $admin->id)->exists()) {
            abort(404, 'Cet utilisateur n\'est pas administrateur de cette école');
        }
        
        $request->validate([
            'role' => 'required|in:admin,manager,finance,secretary',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:manage_students,manage_fees,manage_teachers,manage_programs,manage_reports',
        ]);
        
        $permissions = $request->permissions ?? [];
        
        $school->admins()->updateExistingPivot($admin->id, [
            'role' => $request->role,
            'permissions' => json_encode($permissions),
        ]);
        
        return redirect()->route('schools.show', $school)
            ->with('success', 'Administrateur mis à jour avec succès');
    }
    
    /**
     * Remove the specified admin from the school.
     */
    public function destroy(School $school, User $admin)
    {
        // Vérifier si l'utilisateur a le droit de supprimer des administrateurs
        $this->authorize('manageAdmins', $school);
        
        // Vérifier si l'utilisateur est bien un admin de cette école
        if (!$school->admins()->where('user_id', $admin->id)->exists()) {
            abort(404, 'Cet utilisateur n\'est pas administrateur de cette école');
        }
        
        // Empêcher la suppression du dernier administrateur
        if ($school->admins()->count() <= 1) {
            return redirect()->route('schools.show', $school)
                ->with('error', 'Impossible de supprimer le dernier administrateur');
        }
        
        $school->admins()->detach($admin->id);
        
        return redirect()->route('schools.show', $school)
            ->with('success', 'Administrateur retiré avec succès');
    }
}
