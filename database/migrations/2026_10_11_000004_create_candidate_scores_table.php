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
        Schema::create('candidate_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->unique()->constrained('candidates')->cascadeOnDelete();
            $table->decimal('academic_score', 8, 2)->nullable();
            $table->decimal('btq_score', 8, 2)->nullable();
            $table->decimal('tahfidz_score', 8, 2)->nullable();
            $table->decimal('interview_score', 8, 2)->nullable();
            $table->boolean('is_complete')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_scores');
    }
};
