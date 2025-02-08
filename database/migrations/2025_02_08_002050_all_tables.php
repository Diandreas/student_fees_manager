<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create campuses table
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create fields table
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->decimal('fees', 10, 2)->nullable();
            $table->timestamps();
        });

        // Create students table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('fullName');
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('phone', 45)->nullable();
            $table->string('parent_tel')->nullable();
            $table->foreignId('field_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->text('description');
            $table->string('receipt_number')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop tables in reverse order to respect foreign key constraints
        Schema::dropIfExists('payments');
        Schema::dropIfExists('students');
        Schema::dropIfExists('fields');
        Schema::dropIfExists('campuses');
    }
};
