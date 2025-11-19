<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CleanupOldPlacementConstraints extends Migration
{
    /**
     * Nettoyer les anciennes contraintes et index de exam_student_placements
     * pour finaliser la refactorisation vers exam_id
     *
     * @return void
     */
    public function up()
    {
        // 1. Vider la table pour éviter les conflits
        DB::table('exam_student_placements')->truncate();
        
        // 2. Supprimer l'ancienne foreign key exam_schedule_id si elle existe encore
        $oldForeignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'exam_student_placements' 
            AND COLUMN_NAME = 'exam_schedule_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($oldForeignKeys as $fk) {
            DB::statement("ALTER TABLE exam_student_placements DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
        }
        
        // 3. Supprimer l'ancien index unique exam_schedule_id+student_id
        $oldIndexes = DB::select("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'exam_student_placements' 
            AND INDEX_NAME LIKE '%exam_schedule_id%'
        ");
        
        foreach ($oldIndexes as $index) {
            try {
                DB::statement("ALTER TABLE exam_student_placements DROP INDEX {$index->INDEX_NAME}");
            } catch (\Exception $e) {
                // Ignore si déjà supprimé
            }
        }
        
        // 4. Supprimer la colonne exam_schedule_id si elle existe encore
        if (Schema::hasColumn('exam_student_placements', 'exam_schedule_id')) {
            Schema::table('exam_student_placements', function (Blueprint $table) {
                $table->dropColumn('exam_schedule_id');
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
        // Pas de rollback - cette migration est un nettoyage one-way
    }
}
