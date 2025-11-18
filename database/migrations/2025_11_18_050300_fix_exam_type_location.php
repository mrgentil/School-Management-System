<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * CORRECTION: exam_type doit être sur exam_schedules, pas sur exams
     * Car c'est l'HORAIRE qui détermine si SESSION ou HORS SESSION
     */
    public function up(): void
    {
        // 1. Supprimer exam_type de la table exams
        if (Schema::hasColumn('exams', 'exam_type')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn('exam_type');
            });
        }

        // 2. Ajouter exam_type à la table exam_schedules
        if (!Schema::hasColumn('exam_schedules', 'exam_type')) {
            Schema::table('exam_schedules', function (Blueprint $table) {
                $table->enum('exam_type', ['hors_session', 'session'])
                      ->default('hors_session')
                      ->after('exam_id')
                      ->comment('Hors Session = salle habituelle, Session = placement automatique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retour en arrière
        if (Schema::hasColumn('exam_schedules', 'exam_type')) {
            Schema::table('exam_schedules', function (Blueprint $table) {
                $table->dropColumn('exam_type');
            });
        }

        if (!Schema::hasColumn('exams', 'exam_type')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->enum('exam_type', ['hors_session', 'session'])
                      ->default('hors_session')
                      ->after('semester');
            });
        }
    }
};
