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
        Schema::create('fam_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20); // e.g. R1, R2
            $table->foreignId('academic_membership_id')->constrained('fuzzy_memberships')->cascadeOnDelete();
            $table->foreignId('btq_membership_id')->constrained('fuzzy_memberships')->cascadeOnDelete();
            $table->foreignId('tahfidz_membership_id')->constrained('fuzzy_memberships')->cascadeOnDelete();
            $table->foreignId('interview_membership_id')->constrained('fuzzy_memberships')->cascadeOnDelete();
            $table->foreignId('output_membership_id')->constrained('fuzzy_memberships')->cascadeOnDelete();
            $table->decimal('weight', 4, 2)->default(1.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fam_rules');
    }
};
