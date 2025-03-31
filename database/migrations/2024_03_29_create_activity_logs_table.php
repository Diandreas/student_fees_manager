<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable(); // Type d'utilisateur (admin, étudiant, etc.)
            $table->unsignedBigInteger('user_id')->nullable(); // ID de l'utilisateur
            $table->string('action'); // L'action effectuée
            $table->string('model_type')->nullable(); // Type de modèle affecté
            $table->unsignedBigInteger('model_id')->nullable(); // ID du modèle affecté
            $table->text('description'); // Description détaillée de l'action
            $table->json('old_values')->nullable(); // Anciennes valeurs
            $table->json('new_values')->nullable(); // Nouvelles valeurs
            $table->string('ip_address')->nullable(); // Adresse IP
            $table->string('user_agent')->nullable(); // Navigateur/Appareil
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}; 