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
        Schema::table('students', function (Blueprint $table) {
            // Ajout de la colonne pour la photo
            $table->string('photo')->nullable()->after('field_id');
            
            // Ajout des colonnes pour les informations des parents
            $table->string('parent_name')->nullable()->after('parent_tel');
            $table->string('parent_email')->nullable()->after('parent_name');
            $table->string('parent_profession')->nullable()->after('parent_email');
            $table->text('parent_address')->nullable()->after('parent_profession');
            
            // Ajout d'un contact d'urgence supplémentaire
            $table->string('emergency_contact_name')->nullable()->after('parent_address');
            $table->string('emergency_contact_tel')->nullable()->after('emergency_contact_name');
            $table->string('relationship')->nullable()->after('emergency_contact_tel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Suppression des colonnes ajoutées
            $table->dropColumn([
                'photo', 
                'parent_name', 
                'parent_email', 
                'parent_profession', 
                'parent_address',
                'emergency_contact_name',
                'emergency_contact_tel',
                'relationship'
            ]);
        });
    }
};
