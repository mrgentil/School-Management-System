<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBookRequestsForeignKey extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            echo "\n=== Correction de la clé étrangère de book_requests.student_id ===\n";
            
            // 1. Supprimer la contrainte existante
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'book_requests' 
                AND COLUMN_NAME = 'student_id'
            ", [DB::getDatabaseName()]);
            
            if (!empty($constraints)) {
                $constraintName = $constraints[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `book_requests` DROP FOREIGN KEY `{$constraintName}`");
                echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée.\n";
            }
            
            // 2. Modifier le type de la colonne
            DB::statement("ALTER TABLE `book_requests` MODIFY `student_id` BIGINT UNSIGNED NULL");
            echo "[SUCCÈS] Colonne 'student_id' mise à jour en BIGINT UNSIGNED.\n";
            
            // 3. Recréer la contrainte
            $constraintName = 'book_requests_student_id_foreign';
            DB::statement("
                ALTER TABLE `book_requests`
                ADD CONSTRAINT `{$constraintName}`
                FOREIGN KEY (`student_id`)
                REFERENCES `students` (`user_id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            
            ");
            echo "[SUCCÈS] Contrainte '{$constraintName}' recréée.\n";
            
        } catch (\Exception $e) {
            echo "[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
    
    public function down(): void
    {
        // Laisser vide intentionnellement
    }
}
