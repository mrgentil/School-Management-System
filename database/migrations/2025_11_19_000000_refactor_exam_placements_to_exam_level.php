<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RefactorExamPlacementsToExamLevel extends Migration
{
    /**
     * Run the migrations.
     * LOGIQUE CORRECTE: Un élève a UNE salle et UN numéro de place pour TOUT l'examen SESSION
     * Le placement est au niveau de l'EXAMEN (exam_id), pas de l'horaire (exam_schedule_id)
     *
     * @return void
     */
    public function up()
    {
        // Vérifier et supprimer la foreign key existante si elle existe
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'exam_student_placements' 
            AND COLUMN_NAME = 'exam_schedule_id'
        ");
        
        if (!empty($foreignKeys)) {
            $foreignKeyName = $foreignKeys[0]->CONSTRAINT_NAME;
            DB::statement("ALTER TABLE exam_student_placements DROP FOREIGN KEY {$foreignKeyName}");
        }
        
        Schema::table('exam_student_placements', function (Blueprint $table) {
            // Supprimer la colonne exam_schedule_id si elle existe
            if (Schema::hasColumn('exam_student_placements', 'exam_schedule_id')) {
                $table->dropColumn('exam_schedule_id');
            }
            
            // Ajouter la nouvelle colonne exam_id si elle n'existe pas déjà
            if (!Schema::hasColumn('exam_student_placements', 'exam_id')) {
                $table->unsignedInteger('exam_id')->after('id');
                $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            }
        });
        
        // Ajouter l'index unique s'il n'existe pas
        $indexExists = DB::select("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'exam_student_placements' 
            AND INDEX_NAME = 'unique_student_exam_placement'
        ");
        
        if (empty($indexExists)) {
            Schema::table('exam_student_placements', function (Blueprint $table) {
                $table->unique(['exam_id', 'student_id'], 'unique_student_exam_placement');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_student_placements', function (Blueprint $table) {
            // Retirer l'index unique
            $table->dropUnique('unique_student_exam_placement');
            
            // Retirer exam_id
            $table->dropForeign(['exam_id']);
            $table->dropColumn('exam_id');
            
            // Remettre exam_schedule_id
            $table->unsignedInteger('exam_schedule_id')->after('id');
            $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
        });
    }
}
