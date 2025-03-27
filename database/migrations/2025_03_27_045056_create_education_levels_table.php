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
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Exemples: "Primaire", "Secondaire", "Licence", etc.
            $table->string('code')->nullable(); // Code court pour ce niveau
            $table->string('description')->nullable();
            $table->integer('order')->default(0); // Pour l'ordre d'affichage
            $table->integer('duration_years')->default(1); // Durée typique en années
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};
