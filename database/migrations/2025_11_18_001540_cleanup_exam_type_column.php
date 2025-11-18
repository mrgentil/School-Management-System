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
        // Supprimer la colonne exam_type si elle existe déjà
        if (Schema::hasColumn('exams', 'exam_type')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn('exam_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à faire
    }
};
