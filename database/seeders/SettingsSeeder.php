<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
            
            // Couleurs du thème
            'colors' => json_encode([
                'primary' => '#0A3D62',
                'secondary' => '#1E5B94',
                'accent' => '#D4AF37',
                'dark_blue' => '#071E3D',
            ]),
            
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
            
            // Modes d'affichage par défaut
            'display_modes' => json_encode([
                'students' => 'list',
                'payments' => 'list',
                'fields' => 'list',
                'campuses' => 'list',
            ]),
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