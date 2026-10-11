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
        Schema::create('calculation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calculation_run_id')->constrained('calculation_runs')->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained('candidates')->restrictOnDelete();
            $table->json('fuzzification_data')->nullable();
            $table->json('activated_rules_data')->nullable();
            $table->json('fam_matrix_data')->nullable();
            $table->json('composition_data')->nullable();
            $table->json('defuzzification_data')->nullable();
            $table->decimal('final_score', 8, 4);
            $table->string('decision', 50); // DITERIMA, TIDAK DITERIMA
            $table->unsignedInteger('rank')->nullable();
            $table->timestamps();

            $table->unique(['calculation_run_id', 'candidate_id'], 'calc_run_candidate_unique');
            $table->index(['calculation_run_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_details');
    }
};
