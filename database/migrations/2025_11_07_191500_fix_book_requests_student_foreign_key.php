<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBookRequestsStudentForeignKey extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            echo "\n=== Correction de la clé étrangère book_requests.student_id ===\n";

            // 1. Vérifier et supprimer la contrainte existante
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'book_requests' 
                AND COLUMN_NAME = 'student_id'
                AND CONSTRAINT_NAME = 'book_requests_student_id_foreign'
            ", [DB::getDatabaseName()]);

            if (!empty($constraints)) {
                $constraintName = $constraints[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `book_requests` DROP FOREIGN KEY `{$constraintName}`");
                echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée.\n";
            } else {
                echo "[INFO] Aucune contrainte existante à supprimer.\n";
            }

            // 2. Vérifier et modifier le type de la colonne student_id
            $columnInfo = DB::select("SHOW COLUMNS FROM `book_requests` WHERE Field = 'student_id'")[0] ?? null;
            
            if ($columnInfo) {
                // Vérifier si la colonne n'est pas déjà du bon type
                if (strpos(strtolower($columnInfo->Type), 'bigint') === false) {
                    DB::statement("ALTER TABLE `book_requests` MODIFY `student_id` BIGINT UNSIGNED NULL");
                    echo "[SUCCÈS] Colonne 'student_id' mise à jour en BIGINT UNSIGNED.\n";
                } else {
                    echo "[INFO] La colonne 'student_id' est déjà du type BIGINT UNSIGNED.\n";
                }
            } else {
                echo "[ERREUR] La colonne 'student_id' n'existe pas dans la table 'book_requests'.\n";
                return;
            }

            // 3. Vérifier si la contrainte n'existe pas déjà avant de la recréer
            $existingConstraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'book_requests' 
                AND CONSTRAINT_NAME = 'book_requests_student_id_foreign'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ", [DB::getDatabaseName()]);

            if (empty($existingConstraints)) {
                // 4. Recréer la contrainte
                $constraintName = 'book_requests_student_id_foreign';
                
                // Vérifier si la table students et la colonne user_id existent
                $tableExists = DB::select("SHOW TABLES LIKE 'students'");
                $columnExists = $tableExists ? DB::select("SHOW COLUMNS FROM `students` WHERE Field = 'user_id'") : [];
                
                if (!empty($tableExists) && !empty($columnExists)) {
                    // Si la table students et la colonne user_id existent, référencer students.user_id
                    DB::statement("
                        ALTER TABLE `book_requests`
                        ADD CONSTRAINT `{$constraintName}`
                        FOREIGN KEY (`student_id`)
                        REFERENCES `students` (`user_id`)
                        ON DELETE CASCADE
                        ON UPDATE CASCADE
                    ");
                    echo "[SUCCÈS] Contrainte '{$constraintName}' recréée avec référence à students.user_id.\n";
                } else {
                    // Sinon, référencer users.id (comportement par défaut)
                    DB::statement("
                        ALTER TABLE `book_requests`
                        ADD CONSTRAINT `{$constraintName}`
                        FOREIGN KEY (`student_id`)
                        REFERENCES `users` (`id`)
                        ON DELETE CASCADE
                        ON UPDATE CASCADE
                    ");
                    echo "[SUCCÈS] Contrainte '{$constraintName}' recréée avec référence à users.id.\n";
                }
            } else {
                echo "[INFO] La contrainte 'book_requests_student_id_foreign' existe déjà.\n";
            }

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
        // Le rollback sera géré par les autres migrations
    }
}
