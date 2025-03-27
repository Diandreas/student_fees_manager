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
            }
        });
    }
}
