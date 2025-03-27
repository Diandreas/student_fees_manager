<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            // Ajouter la colonne school_id
            $table->unsignedBigInteger('school_id')->after('id')->nullable();
            
            // Ajouter la clé étrangère
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            
            // Index pour les performances
            $table->index('school_id');
        });
        
        // Mise à jour des enregistrements existants
        DB::statement('
            UPDATE fields 
            SET school_id = (
                SELECT school_id 
                FROM campuses 
                WHERE campuses.id = fields.campus_id
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropIndex(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
