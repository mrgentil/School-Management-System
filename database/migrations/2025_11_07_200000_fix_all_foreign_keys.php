<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixAllForeignKeys extends Migration
{
    public function up(): void
    {
        // Désactiver temporairement les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            echo "\n=== Début de la correction des clés étrangères ===\n";

            // Liste des tables et colonnes avec leurs types cibles
            $tables = [
                'book_loans' => [
                    'user_id' => 'BIGINT UNSIGNED NOT NULL',
                    'book_id' => 'BIGINT UNSIGNED NOT NULL',
                    'issued_by' => 'BIGINT UNSIGNED NULL',
                ],
                'book_requests' => [
                    'student_id' => 'BIGINT UNSIGNED NOT NULL',
                    'book_id' => 'BIGINT UNSIGNED NOT NULL',
                    'approved_by' => 'BIGINT UNSIGNED NULL',
                ],
                'book_reservations' => [
                    'user_id' => 'BIGINT UNSIGNED NOT NULL',
                    'book_id' => 'BIGINT UNSIGNED NOT NULL',
                ],
                'book_reviews' => [
                    'user_id' => 'BIGINT UNSIGNED NOT NULL',
                    'book_id' => 'BIGINT UNSIGNED NOT NULL',
                ],
                'students' => [
                    'user_id' => 'BIGINT UNSIGNED NOT NULL',
                ],
                'staff_records' => [
                    'user_id' => 'BIGINT UNSIGNED NOT NULL',
                ],
                'student_records' => [
                    'user_id' => 'BIGINT UNSIGNED NULL',
                    'my_parent_id' => 'BIGINT UNSIGNED NULL',
                    'dorm_id' => 'BIGINT UNSIGNED NULL',
                ],
                // Ajoutez d'autres tables et colonnes au besoin
            ];

            // 1. Supprimer toutes les contraintes de clés étrangères
            echo "\n=== Étape 1: Suppression des contraintes existantes ===\n";
            foreach ($tables as $tableName => $columns) {
                if (!Schema::hasTable($tableName)) {
                    echo "[INFO] La table '{$tableName}' n'existe pas. Ignorée.\n";
                    continue;
                }

                foreach (array_keys($columns) as $column) {
                    if (!Schema::hasColumn($tableName, $column)) {
                        echo "[INFO] La colonne '{$tableName}.{$column}' n'existe pas. Ignorée.\n";
                        continue;
                    }

                    $constraintName = "{$tableName}_{$column}_foreign";
                    
                    // Vérifier si la contrainte existe
                    $constraintExists = DB::select("
                        SELECT 1 FROM information_schema.TABLE_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = ? 
                        AND TABLE_NAME = ? 
                        AND CONSTRAINT_NAME = ?
                        AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                    ", [DB::getDatabaseName(), $tableName, $constraintName]);

                    if (!empty($constraintExists)) {
                        DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`");
                        echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée.\n";
                    }
                }
            }

            // 2. Mettre à jour les types de colonnes
            echo "\n=== Étape 2: Mise à jour des types de colonnes ===\n";
            foreach ($tables as $tableName => $columns) {
                if (!Schema::hasTable($tableName)) {
                    continue;
                }

                foreach ($columns as $column => $type) {
                    if (!Schema::hasColumn($tableName, $column)) {
                        continue;
                    }

                    // Vérifier si le type de colonne doit être mis à jour
                    $currentType = DB::select("SHOW COLUMNS FROM `{$tableName}` WHERE Field = '{$column}'")[0]->Type;
                    
                    // Normaliser les types pour la comparaison
                    $normalizedCurrent = strtoupper(preg_replace('/\s+/', '', $currentType));
                    $normalizedTarget = strtoupper(preg_replace('/\s+/', '', $type));
                    
                    if ($normalizedCurrent !== $normalizedTarget) {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$column}` {$type}");
                        echo "[SUCCÈS] Colonne '{$tableName}.{$column}' mise à jour en {$type}.\n";
                    } else {
                        echo "[INFO] La colonne '{$tableName}.{$column}' est déjà du type {$type}.\n";
                    }
                }
            }

            // 3. Recréer les contraintes de clés étrangères
            echo "\n=== Étape 3: Recréation des contraintes ===\n";
            
            // Contraintes spéciales qui ne suivent pas la convention de nommage standard
            $specialForeignKeys = [
                'book_requests' => [
                    'student_id' => [
                        'references' => 'students',
                        'on' => 'user_id',
                        'onDelete' => 'CASCADE',
                        'onUpdate' => 'CASCADE',
                    ],
                ],
                // Ajoutez d'autres contraintes spéciales si nécessaire
            ];

            foreach ($tables as $tableName => $columns) {
                if (!Schema::hasTable($tableName)) {
                    continue;
                }

                foreach ($columns as $column => $type) {
                    if (!Schema::hasColumn($tableName, $column)) {
                        continue;
                    }

                    $constraintName = "{$tableName}_{$column}_foreign";
                    
                    // Vérifier si la contrainte existe déjà
                    $constraintExists = DB::select("
                        SELECT 1 FROM information_schema.TABLE_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = ? 
                        AND TABLE_NAME = ? 
                        AND CONSTRAINT_NAME = ?
                        AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                    ", [DB::getDatabaseName(), $tableName, $constraintName]);

                    if (!empty($constraintExists)) {
                        echo "[INFO] La contrainte '{$constraintName}' existe déjà. Ignorée.\n";
                        continue;
                    }

                    // Vérifier s'il s'agit d'une contrainte spéciale
                    if (isset($specialForeignKeys[$tableName][$column])) {
                        $special = $specialForeignKeys[$tableName][$column];
                        
                        DB::statement("
                            ALTER TABLE `{$tableName}`
                            ADD CONSTRAINT `{$constraintName}`
                            FOREIGN KEY (`{$column}`)
                            REFERENCES `{$special['references']}` (`{$special['on']}`)
                            ON DELETE {$special['onDelete']}
                            ON UPDATE {$special['onUpdate']};
                        ");
                        
                        echo "[SUCCÈS] Contrainte spéciale '{$constraintName}' recréée.\n";
                        continue;
                    }

                    // Déterminer la table et la colonne de référence
                    $referenceTable = 'users';
                    $referenceColumn = 'id';
                    
                    // Déterminer l'action ON DELETE
                    $onDelete = 'CASCADE';
                    if (in_array($column, ['approved_by', 'marked_by', 'issued_by', 'created_by', 'uploaded_by'])) {
                        $onDelete = 'SET NULL';
                    }
                    // Cas spécifique: student_records.user_id doit être SET NULL et nullable
                    if ($tableName === 'student_records' && $column === 'user_id') {
                        $onDelete = 'SET NULL';
                    }

                    // Vérifier si la table de référence existe
                    $tableExists = DB::select("SHOW TABLES LIKE '{$referenceTable}'");
                    if (empty($tableExists)) {
                        echo "[ERREUR] La table de référence '{$referenceTable}' n'existe pas. Impossible de créer la contrainte.\n";
                        continue;
                    }

                    // Vérifier si la colonne de référence existe
                    $columnExists = DB::select("SHOW COLUMNS FROM `{$referenceTable}` WHERE Field = '{$referenceColumn}'");
                    if (empty($columnExists)) {
                        echo "[ERREUR] La colonne de référence '{$referenceTable}.{$referenceColumn}' n'existe pas. Impossible de créer la contrainte.\n";
                        continue;
                    }

                    // Créer la contrainte
                    DB::statement("
                        ALTER TABLE `{$tableName}`
                        ADD CONSTRAINT `{$constraintName}`
                        FOREIGN KEY (`{$column}`)
                        REFERENCES `{$referenceTable}` (`{$referenceColumn}`)
                        ON DELETE {$onDelete}
                        ON UPDATE CASCADE;
                    ");
                    
                    echo "[SUCCÈS] Contrainte '{$constraintName}' recréée.\n";
                }
            }

            echo "\n=== Correction des clés étrangères terminée avec succès ===\n";

        } catch (\Exception $e) {
            echo "\n[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            // Réactiver les vérifications de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        // Cette migration est à sens unique car elle corrige des problèmes d'intégrité des données
        // Un rollback pourrait causer des problèmes, donc nous laissons cette méthode vide
    }
}
