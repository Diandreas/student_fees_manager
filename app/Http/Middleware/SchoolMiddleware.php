<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class SchoolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Si aucune école n'est sélectionnée dans la session
        if (!session()->has('current_school_id')) {
            // Pour les super-admin, on prend la première école
            if ($user->is_superadmin) {
                $school = School::first();
                if ($school) {
                    session(['current_school_id' => $school->id]);
                } else {
                    // Rediriger vers la création d'école s'il n'y en a aucune
                    return redirect()->route('schools.create')
                        ->with('info', 'Veuillez créer une école pour commencer');
                }
            } else {
                // Pour les utilisateurs normaux, on prend leur première école
                $school = DB::table('school_admins')
                    ->where('user_id', $user->id)
                    ->join('schools', 'school_admins.school_id', '=', 'schools.id')
                    ->select('schools.*')
                    ->first();
                    
                if ($school) {
                    session(['current_school_id' => $school->id]);
                } else {
                    return redirect()->route('home')
                        ->with('error', 'Vous n\'êtes pas associé à une école');
                }
            }
        } else {
            // Vérifier que l'utilisateur a accès à l'école sélectionnée
            $schoolId = session('current_school_id');
            $school = School::find($schoolId);
            
            if (!$user->is_superadmin) {
                // Vérifier si l'utilisateur est admin de cette école
                $exists = DB::table('school_admins')
                    ->where('user_id', $user->id)
                    ->where('school_id', $schoolId)
                    ->exists();
                
                if (!$exists) {
                    // Si pas accès, on reset la session et on redirige
                    session()->forget('current_school_id');
                    return redirect()->route('home')
                        ->with('error', 'Vous n\'avez pas accès à cette école');
                }
            }
        }
        
        // Le partage de la variable currentSchool est maintenant géré dans AppServiceProvider

        return $next($request);
    }
}
