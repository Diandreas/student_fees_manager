<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Champs pour la personnalisation de l'interface
            $table->string('theme_color')->default('#0d47a1')->after('secondary_color'); // Bleu présidentiel par défaut
            $table->string('header_color')->default('#0d47a1')->after('theme_color');
            $table->string('sidebar_color')->default('#ffffff')->after('header_color');
            $table->string('text_color')->default('#333333')->after('sidebar_color');
            
            // Type d'établissement et personnalisation de la terminologie
            $table->string('school_type')->default('secondary')->after('text_color'); // primary, secondary, university, professional, other
            $table->json('terminology')->nullable()->after('school_type'); // Pour personnaliser les termes (élèves/étudiants, classes/filières, etc.)
            
            // Champs pour les fonctionnalités supplémentaires
            $table->boolean('has_online_payments')->default(false)->after('terminology');
            $table->boolean('has_sms_notifications')->default(false)->after('has_online_payments');
            $table->boolean('has_parent_portal')->default(false)->after('has_sms_notifications');
            
            // Champs pour les préférences
            $table->json('preferences')->nullable()->after('has_parent_portal');
            
            // Champs pour le plan d'abonnement
            $table->string('subscription_plan')->default('basic')->after('preferences'); // basic, premium, enterprise
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'theme_color',
                'header_color',
                'sidebar_color',
                'text_color',
                'school_type',
                'terminology',
                'has_online_payments',
                'has_sms_notifications',
                'has_parent_portal',
                'preferences',
                'subscription_plan',
                'subscription_expires_at',
            ]);
        });
    }
};
