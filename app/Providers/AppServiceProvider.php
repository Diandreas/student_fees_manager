<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Partager l'école courante avec toutes les vues
        // Note: Dans Laravel 11, on préfère cette approche plutôt que d'utiliser view()->share()
        \Illuminate\Support\Facades\View::composer('*', function($view) {
            if (session()->has('current_school_id')) {
                $currentSchool = \App\Models\School::find(session('current_school_id'));
                $view->with('currentSchool', $currentSchool);
                
                // Partager un helper pour la terminologie
                $view->with('term', function($key, $default = null) use ($currentSchool) {
                    return $currentSchool->term($key, $default);
                });
            }
        });

        // Création d'une directive Blade pour la terminologie
        \Illuminate\Support\Facades\Blade::directive('term', function ($expression) {
            return "<?php echo \$currentSchool ? \$currentSchool->term($expression) : $expression; ?>";
        });
    }
}
