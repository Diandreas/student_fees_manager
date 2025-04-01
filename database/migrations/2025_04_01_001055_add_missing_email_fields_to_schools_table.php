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
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('schools', 'email')) {
                $table->string('email')->nullable()->after('contact_email');
            }
            
            if (!Schema::hasColumn('schools', 'phone')) {
                $table->string('phone')->nullable()->after('contact_phone');
            }
            
            if (!Schema::hasColumn('schools', 'currency')) {
                $table->string('currency')->default('XAF')->after('description');
            }
            
            if (!Schema::hasColumn('schools', 'report_settings')) {
                $table->json('report_settings')->nullable()->after('preferences');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            if (Schema::hasColumn('schools', 'email')) {
                $table->dropColumn('email');
            }
            
            if (Schema::hasColumn('schools', 'phone')) {
                $table->dropColumn('phone');
            }
            
            if (Schema::hasColumn('schools', 'currency')) {
                $table->dropColumn('currency');
            }
            
            if (Schema::hasColumn('schools', 'report_settings')) {
                $table->dropColumn('report_settings');
            }
        });
    }
};
