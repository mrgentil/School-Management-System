<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_student_placements', function (Blueprint $table) {
            // Vérifier si la colonne exam_id existe déjà
            if (!Schema::hasColumn('exam_student_placements', 'exam_id')) {
                // Ajouter la colonne exam_id
                $table->unsignedBigInteger('exam_id')->after('id');
                
                // Si exam_schedule_id existe, migrer les données
                if (Schema::hasColumn('exam_student_placements', 'exam_schedule_id')) {
                    // Mettre à jour exam_id basé sur exam_schedule_id
                    DB::statement('
                        UPDATE exam_student_placements esp 
                        JOIN exam_schedules es ON esp.exam_schedule_id = es.id 
                        SET esp.exam_id = es.exam_id
                    ');
                    
                    // Supprimer l'ancienne colonne
                    $table->dropForeign(['exam_schedule_id']);
                    $table->dropColumn('exam_schedule_id');
                }
                
                // Ajouter la contrainte de clé étrangère
                $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_student_placements', function (Blueprint $table) {
            if (Schema::hasColumn('exam_student_placements', 'exam_id')) {
                $table->dropForeign(['exam_id']);
                $table->dropColumn('exam_id');
                
                // Recréer exam_schedule_id si nécessaire
                $table->unsignedBigInteger('exam_schedule_id')->after('id');
                $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
            }
        });
    }
};
