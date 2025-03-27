<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Si l'utilisateur est super-admin, on montre toutes les écoles
        // Sinon, on montre uniquement les écoles où il est admin
        $user = Auth::user();
        $schools = $user->is_superadmin 
            ? School::all() 
            : $user->schools;
            
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier si l'utilisateur est super-admin
        $this->authorize('create', School::class);
        
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est super-admin
        $this->authorize('create', School::class);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $data = $request->except('logo');
        
        // Générer un sous-domaine à partir du nom
        $data['subdomain'] = Str::slug($request->name);
        
        // Traiter le logo s'il est fourni
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('schools/logos', 'public');
            $data['logo'] = $path;
        }
        
        // Créer l'école
        $school = School::create($data);
        
        // Si ce n'est pas un super-admin, on ajoute l'utilisateur comme admin de l'école
        $user = Auth::user();
        if (!$user->is_superadmin) {
            $school->admins()->attach($user->id, ['role' => 'admin']);
        }
        
        return redirect()->route('schools.show', $school)
            ->with('success', 'École créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        // Vérifier si l'utilisateur a accès à cette école
        $this->authorize('view', $school);
        
        // Stocker l'école actuelle dans la session
        session(['current_school_id' => $school->id]);
        
        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        // Vérifier si l'utilisateur a le droit de modifier cette école
        $this->authorize('update', $school);
        
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        // Vérifier si l'utilisateur a le droit de modifier cette école
        $this->authorize('update', $school);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $data = $request->except(['logo', '_token', '_method']);
        
        // Traiter le logo s'il est fourni
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }
            
            $path = $request->file('logo')->store('schools/logos', 'public');
            $data['logo'] = $path;
        }
        
        $school->update($data);
        
        return redirect()->route('schools.show', $school)
            ->with('success', 'École mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // Vérifier si l'utilisateur a le droit de supprimer cette école
        $this->authorize('delete', $school);
        
        // Supprimer le logo si nécessaire
        if ($school->logo) {
            Storage::disk('public')->delete($school->logo);
        }
        
        $school->delete();
        
        return redirect()->route('schools.index')
            ->with('success', 'École supprimée avec succès');
    }
    
    /**
     * Switch to the specified school.
     */
    public function switchSchool(School $school)
    {
        // Vérifier si l'utilisateur a accès à cette école
        $user = Auth::user();
        if (!$user->is_superadmin) {
            // Vérifier si l'utilisateur est admin de cette école
            $exists = DB::table('school_admins')
                ->where('user_id', $user->id)
                ->where('school_id', $school->id)
                ->exists();
            
            if (!$exists) {
                abort(403, 'Vous n\'avez pas accès à cette école');
            }
        }
        
        // Stocker l'école actuelle dans la session
        session(['current_school_id' => $school->id]);
        session(['current_school' => $school]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Vous êtes maintenant connecté à l\'école ' . $school->name);
    }
    
    /**
     * Show the school admins management page.
     */
    public function admins(School $school)
    {
        // Vérifier si l'utilisateur a accès à cette école
        $this->authorize('viewAdmins', $school);
        
        $admins = $school->admins;
        $users = User::whereDoesntHave('schools', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->get();
        
        return view('schools.admins', compact('school', 'admins', 'users'));
    }
    
    /**
     * Add an admin to a school.
     */
    public function addAdmin(Request $request, School $school)
    {
        // Vérifier si l'utilisateur a le droit d'ajouter des admins
        $this->authorize('manageAdmins', $school);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,manager,viewer',
        ]);
        
        $school->admins()->attach($request->user_id, ['role' => $request->role]);
        
        return redirect()->route('schools.admins', $school)
            ->with('success', 'Administrateur ajouté avec succès');
    }
    
    /**
     * Remove an admin from a school.
     */
    public function removeAdmin(Request $request, School $school)
    {
        // Vérifier si l'utilisateur a le droit de supprimer des admins
        $this->authorize('manageAdmins', $school);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Empêcher la suppression du dernier admin
        if ($school->admins()->count() <= 1 && $school->admins()->where('users.id', $request->user_id)->exists()) {
            return redirect()->route('schools.admins', $school)
                ->with('error', 'Impossible de supprimer le dernier administrateur');
        }
        
        $school->admins()->detach($request->user_id);
        
        return redirect()->route('schools.admins', $school)
            ->with('success', 'Administrateur supprimé avec succès');
    }

    /**
     * Affiche la liste des écoles disponibles pour sélection
     *
     * @return \Illuminate\Http\Response
     */
    public function select()
    {
        $user = Auth::user();
        
        // Si c'est un super admin, afficher toutes les écoles
        if ($user->is_superadmin) {
            $schools = School::all();
        } else {
            // Sinon, uniquement celles où il est administrateur
            $schools = $user->schools;
        }
        
        // Si une seule école est disponible, la sélectionner automatiquement
        if ($schools->count() == 1) {
            $school = $schools->first();
            session(['current_school_id' => $school->id]);
            session(['current_school' => $school]);
            
            return redirect()->route('dashboard')
                ->with('info', 'École ' . $school->name . ' sélectionnée automatiquement.');
        }
        
        return view('schools.select', compact('schools'));
    }
}
