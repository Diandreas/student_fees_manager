<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ApplySchoolTerminology
{
    /**
     * Partage la terminologie personnalisée de l'école avec toutes les vues
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $school = session('current_school');
        
        // Préparer un tableau associatif des termes personnalisés
        $terms = $this->getBaseTerminology();
        
        if ($school) {
            // Si l'école a une terminologie personnalisée, l'utiliser
            if (!empty($school->terminology) && is_array($school->terminology)) {
                $terms = array_merge($terms, $school->terminology);
            }
            
            // Partager avec toutes les vues
            View::share('terms', $terms);
            
            // Créer une fonction d'aide pour obtenir les termes
            View::composer('*', function ($view) use ($school) {
                $view->with('term', function($key, $default = null) use ($school) {
                    return $school->term($key, $default);
                });
            });
        } else {
            // Partager avec toutes les vues même sans école
            View::share('terms', $terms);
            
            // Créer une fonction d'aide pour obtenir les termes sans école
            View::composer('*', function ($view) use ($terms) {
                $view->with('term', function($key, $default = null) use ($terms) {
                    return $default ?? ($terms[$key] ?? ucfirst($key));
                });
            });
        }
        
        return $next($request);
    }
    
    /**
     * Obtenir la terminologie de base
     */
    protected function getBaseTerminology(): array
    {
        return [
            'student' => 'Étudiant',
            'students' => 'Étudiants',
            'field' => 'Filière',
            'fields' => 'Filières',
            'campus' => 'Campus',
            'campuses' => 'Campus',
            'payment' => 'Paiement',
            'payments' => 'Paiements',
            'class' => 'Classe',
            'classes' => 'Classes',
            'teacher' => 'Enseignant',
            'teachers' => 'Enseignants',
            'parent' => 'Parent',
            'parents' => 'Parents',
            'fee' => 'Frais',
            'fees' => 'Frais',
            'dashboard' => 'Tableau de bord',
            'reports' => 'Rapports',
            'fully_paid' => 'Payé intégralement',
            'partially_paid' => 'Partiellement payé',
            'no_payment' => 'Aucun paiement',
            'paid' => 'Payé',
            'partial' => 'Partiel',
            'unpaid' => 'Non payé',
            'status' => 'État',
            'remaining' => 'Reste à payer',
            'paid_amount' => 'Montant payé',
            'report' => 'Rapport',
            'receipt' => 'Reçu',
            'statistics' => 'Statistiques',
            'profile' => 'Profil',
            'settings' => 'Paramètres',
            'administration' => 'Administration',
            'summary' => 'Résumé',
            'details' => 'Détails',
            'academic_year' => 'Année académique',
            'semester' => 'Semestre',
            'enrollment' => 'Inscription',
            'registration' => 'Enregistrement',
            'document' => 'Document',
            'documents' => 'Documents',
        ];
    }
} 