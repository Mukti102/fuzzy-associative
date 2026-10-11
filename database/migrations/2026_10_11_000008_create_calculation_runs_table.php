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
        Schema::create('calculation_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selection_period_id')->constrained('selection_periods')->restrictOnDelete();
            $table->foreignId('run_by_user_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('run_at');
            $table->string('encoding_method', 50)->default('correlation-minimum');
            $table->string('composition_method', 50)->default('max-min');
            $table->string('defuzzification_method', 50)->default('weighted-average');
            $table->json('config_snapshot')->nullable();
            $table->unsignedInteger('total_candidates')->default(0);
            $table->unsignedInteger('passed_candidates')->default(0);
            $table->unsignedInteger('failed_candidates')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_runs');
    }
};
