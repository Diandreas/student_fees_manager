<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Affiche la page des paramètres généraux.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Récupérer les paramètres de la base de données
            $settings = Setting::pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            // En cas d'erreur (table non existante), utiliser la configuration par défaut
            $settings = config('settings');
        }
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Met à jour les paramètres d'apparence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAppearance(Request $request)
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'default_theme' => ['required', 'string', 'in:light,dark,auto'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:ico,png', 'max:1024'],
        ]);

        // Traitement du logo
        if ($request->hasFile('logo')) {
            $logoName = 'logo.' . $request->logo->extension();
            $request->logo->storeAs('images', $logoName, 'public');
            $this->updateSetting('app_logo', $logoName);
        }

        // Traitement du favicon
        if ($request->hasFile('favicon')) {
            $faviconName = 'favicon.' . $request->favicon->extension();
            $request->favicon->storeAs('', $faviconName, 'public');
            $this->updateSetting('app_favicon', $faviconName);
        }

        // Mise à jour des autres paramètres d'apparence
        $this->updateSetting('app_name', $request->app_name);
        $this->updateSetting('default_theme', $request->default_theme);
        $this->updateSetting('show_footer', $request->has('show_footer') ? 1 : 0);
        $this->updateSetting('show_breadcrumbs', $request->has('show_breadcrumbs') ? 1 : 0);
        $this->updateSetting('enable_animations', $request->has('enable_animations') ? 1 : 0);

        // Mise à jour du nom de l'application dans le fichier .env
        $this->updateEnvVariable('APP_NAME', '"' . $request->app_name . '"');

        return redirect()->route('settings.index', ['#appearance'])->with('appearance_success', 'Les paramètres d\'apparence ont été mis à jour avec succès.');
    }

    /**
     * Met à jour les paramètres de notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_from' => ['required', 'email'],
            'email_name' => ['required', 'string', 'max:255'],
            'email_notifications' => ['nullable', 'array'],
        ]);

        $this->updateSetting('email_from', $request->email_from);
        $this->updateSetting('email_name', $request->email_name);
        $this->updateSetting('email_notifications', json_encode($request->email_notifications ?? []));

        // Mise à jour des variables d'environnement pour le mail
        $this->updateEnvVariable('MAIL_FROM_ADDRESS', $request->email_from);
        $this->updateEnvVariable('MAIL_FROM_NAME', '"' . $request->email_name . '"');

        return redirect()->route('settings.index', ['#notifications'])->with('notifications_success', 'Les paramètres de notifications ont été mis à jour avec succès.');
    }

    /**
     * Met à jour les paramètres de langue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'default_language' => ['required', 'string', 'in:fr,en'],
            'available_languages' => ['required', 'array'],
        ]);

        $this->updateSetting('default_language', $request->default_language);
        $this->updateSetting('available_languages', json_encode($request->available_languages));

        // Mise à jour de la locale par défaut
        $this->updateEnvVariable('APP_LOCALE', $request->default_language);

        return redirect()->route('settings.index', ['#language'])->with('language_success', 'Les paramètres de langue ont été mis à jour avec succès.');
    }

    /**
     * Met à jour les paramètres d'exportation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateExport(Request $request)
    {
        $request->validate([
            'paper_size' => ['required', 'string', 'in:a4,letter,legal'],
            'export_format' => ['required', 'string', 'in:xlsx,csv,pdf'],
            'receipt_footer' => ['nullable', 'string', 'max:500'],
        ]);

        $this->updateSetting('paper_size', $request->paper_size);
        $this->updateSetting('export_format', $request->export_format);
        $this->updateSetting('receipt_footer', $request->receipt_footer);
        $this->updateSetting('include_logo', $request->has('include_logo') ? 1 : 0);

        return redirect()->route('settings.index', ['#export'])->with('export_success', 'Les paramètres d\'exportation ont été mis à jour avec succès.');
    }

    /**
     * Met à jour les paramètres avancés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdvanced(Request $request)
    {
        $request->validate([
            'items_per_page' => ['required', 'integer', 'in:10,25,50,100'],
            'date_format' => ['required', 'string', 'in:d/m/Y,Y-m-d,m/d/Y'],
            'currency' => ['required', 'string', 'max:3'],
        ]);

        $this->updateSetting('items_per_page', $request->items_per_page);
        $this->updateSetting('date_format', $request->date_format);
        $this->updateSetting('currency', $request->currency);
        $this->updateSetting('maintenance_mode', $request->has('maintenance_mode') ? 1 : 0);

        // Si le mode maintenance est activé, mettre à jour le fichier .env
        if ($request->has('maintenance_mode')) {
            Artisan::call('down');
        } else {
            Artisan::call('up');
        }

        return redirect()->route('settings.index', ['#advanced'])->with('advanced_success', 'Les paramètres avancés ont été mis à jour avec succès.');
    }

    /**
     * Met à jour un paramètre dans la base de données.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    private function updateSetting($key, $value)
    {
        try {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        } catch (\Exception $e) {
            // Si la table n'existe pas, stocker en session
            session(['settings.'.$key => $value]);
        }
    }

    /**
     * Met à jour une variable dans le fichier .env.
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    private function updateEnvVariable($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Si la clé existe, remplacer sa valeur
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace('/' . $key . '=.*/', $key . '=' . $value, $content);
            } else {
                // Sinon, ajouter la clé et sa valeur à la fin du fichier
                $content .= "\n" . $key . '=' . $value;
            }

            file_put_contents($path, $content);
        }
    }
} 