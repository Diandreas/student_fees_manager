<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Informations utilisateur
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
            // Informations école
            'school_name' => ['required', 'string', 'max:255'],
            'school_email' => ['required', 'string', 'email', 'max:255', 'unique:schools,contact_email'],
            'school_phone' => ['nullable', 'string', 'max:20'],
            'school_address' => ['nullable', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Commencer une transaction pour s'assurer que tout est créé correctement
        DB::beginTransaction();
        
        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            // Créer l'école associée
            $school = School::create([
                'name' => $data['school_name'],
                'contact_email' => $data['school_email'],
                'contact_phone' => $data['school_phone'] ?? null,
                'address' => $data['school_address'] ?? null,
                'primary_color' => '#0d47a1', // Couleur par défaut
                'secondary_color' => '#10b981',
                'theme_color' => '#0d47a1',
                'header_color' => '#0d47a1',
                'sidebar_color' => '#ffffff',
                'text_color' => '#333333',
                'subdomain' => Str::slug($data['school_name']),
                'is_active' => true,
                'subscription_plan' => 'basic',
            ]);
            
            // Associer l'utilisateur comme administrateur de l'école
            $school->admins()->attach($user->id, [
                'role' => 'admin',
                'permissions' => json_encode(['manage_students', 'manage_fees', 'manage_teachers', 'manage_programs', 'manage_reports']),
            ]);
            
            // Enregistrer l'école actuelle dans la session
            session(['current_school_id' => $school->id]);
            
            // Valider la transaction
            DB::commit();
            
            return $user;
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();
            
            throw $e;
        }
    }
    
    /**
     * Override du registre pour personnaliser le processus
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? response()->json(['redirect' => $this->redirectPath()])
                    : redirect($this->redirectPath())->with('success', 'Votre compte et votre établissement ont été créés avec succès!');
    }
}
