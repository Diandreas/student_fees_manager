<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\School;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Charger les paramètres dans toutes les vues
        $this->loadSettings();

        // Partager l'école actuelle avec toutes les vues
        view()->composer('*', function ($view) {
            if (session()->has('current_school')) {
                $view->with('currentSchool', session('current_school'));
            }
        });
        
        // Personnaliser les modèles d'email en fonction de l'école
        view()->composer('emails.*', function ($view) {
            $school = null;
            
            // Si l'email est destiné à un étudiant, utiliser son école
            if (isset($view->getData()['student'])) {
                $student = $view->getData()['student'];
                $school = $student->field->campus->school;
            }
            // Si l'email est destiné à un paiement, utiliser l'école de l'étudiant
            elseif (isset($view->getData()['payment'])) {
                $payment = $view->getData()['payment'];
                $school = $payment->student->field->campus->school;
            }
            // Sinon, utiliser l'école actuelle de la session si disponible
            elseif (session()->has('current_school')) {
                $school = session('current_school');
            }
            
            if ($school) {
                $view->with('school', $school);
            }
        });

        // Partager un helper pour la terminologie
        \Illuminate\Support\Facades\View::composer('*', function($view) {
            if (session()->has('current_school_id')) {
                // L'école complète est désormais stockée dans la session
                $currentSchool = session('current_school');
                
                // Si pour une raison quelconque l'école n'est pas dans la session, on la récupère
                if (!$currentSchool) {
                    $currentSchool = School::find(session('current_school_id'));
                    if ($currentSchool) {
                        session(['current_school' => $currentSchool]);
                    }
                }
                
                $view->with('currentSchool', $currentSchool);
                
                // Partager un helper pour la terminologie
                $view->with('term', function($key, $default = null) use ($currentSchool) {
                    return $currentSchool ? $currentSchool->term($key, $default) : $default ?? $key;
                });
            } else {
                // Assurer que $term est toujours disponible même sans école sélectionnée
                $view->with('term', function($key, $default = null) {
                    return $default ?? ucfirst($key);
                });
            }
        });

        // Création d'une directive Blade pour la terminologie
        \Illuminate\Support\Facades\Blade::directive('term', function ($expression) {
            return "<?php echo \$currentSchool ? \$currentSchool->term($expression) : $expression; ?>";
        });

        // Helper pour récupérer un terme de la terminologie de l'école actuelle
        if (!function_exists('school_term')) {
            function school_term($key, $default = null) {
                $school = session('current_school');
                return $school ? $school->term($key, $default) : ($default ?? ucfirst($key));
            }
        }
    }
    
    /**
     * Charge les paramètres de l'application dans toutes les vues
     */
    private function loadSettings()
    {
        try {
            $settings = Setting::pluck('value', 'key')->toArray();
            
            // Charger les couleurs du thème
            $themeColors = [
                'primary' => $settings['primary_color'] ?? '#0A3D62',
                'secondary' => $settings['secondary_color'] ?? '#1E5B94',
                'accent' => $settings['accent_color'] ?? '#D4AF37',
                'dark_blue' => $settings['dark_blue'] ?? '#071E3D',
            ];
            
            View::share('settings', $settings);
            View::share('themeColors', $themeColors);
        } catch (\Exception $e) {
            // En cas d'erreur (table non existante), utiliser des valeurs par défaut
            $themeColors = [
                'primary' => '#0A3D62',
                'secondary' => '#1E5B94',
                'accent' => '#D4AF37',
                'dark_blue' => '#071E3D',
            ];
            
            View::share('settings', []);
            View::share('themeColors', $themeColors);
        }
    }
}
