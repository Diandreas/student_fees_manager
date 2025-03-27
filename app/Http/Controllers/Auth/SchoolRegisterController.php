<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class SchoolRegisterController extends Controller
{
    /**
     * Afficher le formulaire d'inscription pour une école.
     */
    public function showRegistrationForm()
    {
        return view('auth.school-register');
    }
    
    /**
     * Traiter l'inscription d'une école.
     */
    public function register(Request $request)
    {
        $request->validate([
            // Informations sur l'école
            'school_name' => ['required', 'string', 'max:255'],
            'school_email' => ['required', 'string', 'email', 'max:255', 'unique:schools,contact_email'],
            'school_phone' => ['nullable', 'string', 'max:20'],
            'school_address' => ['nullable', 'string', 'max:255'],
            
            // Informations sur l'administrateur
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Commencer une transaction pour s'assurer que tout est créé correctement
        DB::beginTransaction();
        
        try {
            // Créer l'école
            $school = School::create([
                'name' => $request->school_name,
                'contact_email' => $request->school_email,
                'contact_phone' => $request->school_phone,
                'address' => $request->school_address,
                'primary_color' => config('schools.default_colors.primary'),
                'secondary_color' => config('schools.default_colors.secondary'),
                'theme_color' => '#0d47a1', // Bleu présidentiel
                'header_color' => '#0d47a1',
                'sidebar_color' => '#ffffff',
                'text_color' => '#333333',
                'subdomain' => Str::slug($request->school_name),
                'is_active' => true,
                'subscription_plan' => 'basic',
            ]);
            
            // Créer l'utilisateur administrateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Associer l'utilisateur comme administrateur de l'école
            $school->admins()->attach($user->id, [
                'role' => 'admin',
                'permissions' => json_encode(['manage_students', 'manage_fees', 'manage_teachers', 'manage_programs', 'manage_reports']),
            ]);
            
            // Valider la transaction
            DB::commit();
            
            // Déclencher l'événement d'inscription
            event(new Registered($user));
            
            // Connecter l'utilisateur
            Auth::login($user);
            
            // Définir l'école actuelle dans la session
            session(['current_school_id' => $school->id]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Votre école a été enregistrée avec succès !');
                
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.',
            ])->withInput();
        }
    }
}
