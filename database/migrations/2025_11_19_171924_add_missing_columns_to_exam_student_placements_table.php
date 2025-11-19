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
        Schema::table('exam_student_placements', function (Blueprint $table) {
            // Ajouter les colonnes manquantes pour le système de placement
            $table->decimal('ranking_score', 5, 2)->nullable()->after('seat_number');
            $table->enum('performance_level', ['excellence', 'moyen', 'faible'])->nullable()->after('ranking_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_student_placements', function (Blueprint $table) {
            $table->dropColumn(['ranking_score', 'performance_level']);
        });
    }
};
