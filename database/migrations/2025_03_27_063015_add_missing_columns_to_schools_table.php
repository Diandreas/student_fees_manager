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
            // Ajout des colonnes manquantes
            if (!Schema::hasColumn('schools', 'theme_color')) {
                $table->string('theme_color')->default('#0d47a1')->after('secondary_color');
            }
            
            if (!Schema::hasColumn('schools', 'header_color')) {
                $table->string('header_color')->default('#0d47a1')->after('theme_color');
            }
            
            if (!Schema::hasColumn('schools', 'sidebar_color')) {
                $table->string('sidebar_color')->default('#ffffff')->after('header_color');
            }
            
            if (!Schema::hasColumn('schools', 'text_color')) {
                $table->string('text_color')->default('#333333')->after('sidebar_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Suppression des colonnes ajoutées
            $table->dropColumn(['theme_color', 'header_color', 'sidebar_color', 'text_color']);
        });
    }
};
