<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixFinalForeignKeys extends Migration
{
    public function up(): void
    {
        // Désactiver temporairement les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            echo "\n=== Correction des problèmes de clés étrangères finales ===\n";
            
            // 1. Supprimer la contrainte problématique dans book_requests
            if (Schema::hasTable('book_requests') && Schema::hasColumn('book_requests', 'student_id')) {
                try {
                    // Obtenir le nom de la contrainte
                    $constraints = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_NAME = 'book_requests' 
                        AND COLUMN_NAME = 'student_id'
                    ", [DB::getDatabaseName()]);
                    
                    if (!empty($constraints)) {
                        $constraintName = $constraints[0]->CONSTRAINT_NAME;
                        
                        // Supprimer la contrainte
                        DB::statement("ALTER TABLE `book_requests` DROP FOREIGN KEY `{$constraintName}`");
                        echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée de la table 'book_requests'.\n";
                    }
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de supprimer la contrainte de la table 'book_requests': " . $e->getMessage() . "\n";
                }
            }
            
            // 2. Mettre à jour le type de la colonne students.user_id
            if (Schema::hasTable('students') && Schema::hasColumn('students', 'user_id')) {
                try {
                    DB::statement("ALTER TABLE `students` MODIFY `user_id` BIGINT UNSIGNED NULL");
                    echo "[SUCCÈS] Colonne 'user_id' de la table 'students' mise à jour en BIGINT UNSIGNED.\n";
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de mettre à jour la colonne 'user_id' de la table 'students': " . $e->getMessage() . "\n";
                }
            }
            
            // 3. Recréer la contrainte pour students.user_id
            if (Schema::hasTable('students') && Schema::hasColumn('students', 'user_id')) {
                try {
                    $constraintName = 'students_user_id_foreign';
                    
                    // Vérifier si la contrainte existe déjà
                    $constraints = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_NAME = 'students' 
                        AND COLUMN_NAME = 'user_id'
                        AND CONSTRAINT_NAME = '{$constraintName}'
                    ", [DB::getDatabaseName()]);
                    
                    if (empty($constraints)) {
                        // Créer la contrainte
                        DB::statement("\n                            ALTER TABLE `students`\n                            ADD CONSTRAINT `{$constraintName}`\n                            FOREIGN KEY (`user_id`)\n                            REFERENCES `users` (`id`)\n                            ON DELETE CASCADE\n                            ON UPDATE CASCADE\n                        
                        ");
                        echo "[SUCCÈS] Contrainte '{$constraintName}' recréée pour la colonne 'user_id' dans la table 'students'.\n";
                    } else {
                        echo "[DÉJÀ FAIT] La contrainte '{$constraintName}' existe déjà dans la table 'students'.\n";
                    }
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de recréer la contrainte pour 'user_id' dans la table 'students': " . $e->getMessage() . "\n";
                }
            }
            
            // 4. Recréer la contrainte pour book_requests.student_id
            if (Schema::hasTable('book_requests') && Schema::hasColumn('book_requests', 'student_id')) {
                try {
                    $constraintName = 'book_requests_student_id_foreign';
                    
                    // Vérifier si la contrainte existe déjà
                    $constraints = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_NAME = 'book_requests' 
                        AND COLUMN_NAME = 'student_id'
                    ", [DB::getDatabaseName()]);
                    
                    if (empty($constraints)) {
                        // Créer la contrainte
                        DB::statement("\n                            ALTER TABLE `book_requests`\n                            ADD CONSTRAINT `{$constraintName}`\n                            FOREIGN KEY (`student_id`)\n                            REFERENCES `students` (`user_id`)\n                            ON DELETE CASCADE\n                            ON UPDATE CASCADE\n                        
                        ");
                        echo "[SUCCÈS] Contrainte '{$constraintName}' recréée pour la colonne 'student_id' dans la table 'book_requests'.\n";
                    } else {
                        echo "[DÉJÀ FAIT] La contrainte '{$constraintName}' existe déjà dans la table 'book_requests'.\n";
                    }
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de recréer la contrainte pour 'student_id' dans la table 'book_requests': " . $e->getMessage() . "\n";
                }
            }
            
            echo "\n=== Correction des problèmes de clés étrangères terminée ===\n";
            
        } catch (\Exception $e) {
            echo "\n=== ERREUR LORS DE LA MIGRATION ===\n";
            echo "Erreur: " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            // Réactiver les vérifications de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
    
    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback sera géré par les autres migrations
    }
}
