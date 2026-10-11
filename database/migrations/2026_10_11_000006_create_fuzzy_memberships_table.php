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
        Schema::create('fuzzy_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuzzy_variable_id')->constrained('fuzzy_variables')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('curve_type', 30); // triangular, trapezoidal, shoulder_left, shoulder_right
            $table->decimal('param_a', 8, 4);
            $table->decimal('param_b', 8, 4);
            $table->decimal('param_c', 8, 4)->nullable();
            $table->decimal('param_d', 8, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuzzy_memberships');
    }
};
