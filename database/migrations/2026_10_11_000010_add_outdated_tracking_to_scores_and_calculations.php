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
        Schema::table('candidate_scores', function (Blueprint $table) {
            $table->boolean('needs_recalculation')->default(false)->after('is_complete');
        });

        Schema::table('calculation_details', function (Blueprint $table) {
            $table->boolean('is_outdated')->default(false)->after('rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_scores', function (Blueprint $table) {
            $table->dropColumn('needs_recalculation');
        });

        Schema::table('calculation_details', function (Blueprint $table) {
            $table->dropColumn('is_outdated');
        });
    }
};
