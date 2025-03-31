<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearlyStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yearly_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            
            // Statistiques des étudiants
            $table->integer('total_students')->default(0);
            $table->integer('new_students')->default(0);
            $table->integer('graduated_students')->default(0);
            
            // Statistiques financières
            $table->decimal('total_invoiced', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('total_remaining', 15, 2)->default(0);
            $table->decimal('recovery_rate', 5, 2)->default(0);
            
            // Statistiques par campus
            $table->json('campus_stats')->nullable();
            
            // Statistiques par filière
            $table->json('field_stats')->nullable();
            
            // Statistiques mensuelles
            $table->json('monthly_payments')->nullable();
            
            // Relation avec l'archive
            $table->foreignId('archive_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamps();
            
            // Index pour les recherches fréquentes
            $table->index(['school_id', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yearly_stats');
    }
} 