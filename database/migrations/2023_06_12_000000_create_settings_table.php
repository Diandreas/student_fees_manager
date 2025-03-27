<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    /**
     * Exécuter les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Ajouter les paramètres par défaut
        $this->seedDefaultSettings();
    }

    /**
     * Inverser les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Ajouter les paramètres par défaut.
     *
     * @return void
     */
    private function seedDefaultSettings()
    {
        $settings = [
            // Apparence
            'app_name' => 'Student Fees Manager',
            'app_logo' => 'logo.png',
            'app_favicon' => 'favicon.ico',
            'default_theme' => 'light',
            'show_footer' => 1,
            'show_breadcrumbs' => 1,
            'enable_animations' => 1,
            
            // Notifications
            'email_from' => 'noreply@example.com',
            'email_name' => 'Student Fees Manager',
            'email_notifications' => json_encode(['new_student', 'new_payment', 'payment_due']),
            
            // Langue
            'default_language' => 'fr',
            'available_languages' => json_encode(['fr', 'en']),
            
            // Export
            'paper_size' => 'a4',
            'export_format' => 'xlsx',
            'receipt_footer' => 'Merci pour votre paiement. Ce reçu est généré automatiquement et ne nécessite pas de signature.',
            'include_logo' => 1,
            
            // Paramètres avancés
            'items_per_page' => 10,
            'date_format' => 'd/m/Y',
            'currency' => 'XOF',
            'maintenance_mode' => 0,
        ];

        $now = now();
        
        foreach ($settings as $key => $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
} 