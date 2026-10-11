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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selection_period_id')->constrained('selection_periods')->restrictOnDelete();
            $table->string('registration_number', 50);
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('school_origin')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamps();

            $table->unique(['selection_period_id', 'registration_number'], 'candidates_period_reg_unique');
            $table->index(['selection_period_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
