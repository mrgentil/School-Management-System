<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixForeignKeysComprehensive extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            echo "\n=== Début de la correction complète des clés étrangères ===\n";

            // 1. D'abord, supprimer toutes les contraintes problématiques
            $this->dropProblematicForeignKeys();

            // 2. Mettre à jour les types de colonnes
            $this->updateColumnTypes();

            // 3. Recréer les contraintes
            $this->recreateForeignKeys();

            echo "\n=== Correction des clés étrangères terminée avec succès ===\n";

        } catch (\Exception $e) {
            echo "\n[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function dropProblematicForeignKeys(): void
    {
        echo "\n=== Étape 1: Suppression des contraintes problématiques ===\n";
        
        $tables = [
            'book_loans',
            'book_requests',
            'book_reservations',
            'book_reviews',
            'students',
            'staff_records',
            'student_records'
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                echo "[INFO] La table '{$table}' n'existe pas. Ignorée.\n";
                continue;
            }

            // Récupérer toutes les contraintes de clé étrangère pour cette table
            $constraints = DB::select("
                SELECT 
                    CONSTRAINT_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = ? 
                    AND TABLE_NAME = ?
                    AND CONSTRAINT_NAME <> 'PRIMARY'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [DB::getDatabaseName(), $table]);

            foreach ($constraints as $constraint) {
                try {
                    DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint->CONSTRAINT_NAME}`");
                    echo "[SUCCÈS] Contrainte '{$constraint->CONSTRAINT_NAME}' supprimée de la table '{$table}'.\n";
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de supprimer la contrainte '{$constraint->CONSTRAINT_NAME}': " . $e->getMessage() . "\n";
                }
            }
        }
    }

    private function updateColumnTypes(): void
    {
        echo "\n=== Étape 2: Mise à jour des types de colonnes ===\n";

        $columns = [
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
            'students' => [
                'user_id' => 'BIGINT UNSIGNED NOT NULL',
            ],
            // Ajoutez d'autres tables et colonnes au besoin
        ];

        foreach ($columns as $table => $columnDefs) {
            if (!Schema::hasTable($table)) {
                echo "[INFO] La table '{$table}' n'existe pas. Ignorée.\n";
                continue;
            }

            foreach ($columnDefs as $column => $type) {
                if (!Schema::hasColumn($table, $column)) {
                    echo "[INFO] La colonne '{$table}.{$column}' n'existe pas. Ignorée.\n";
                    continue;
                }

                try {
                    $currentType = DB::select("SHOW COLUMNS FROM `{$table}` WHERE Field = '{$column}'")[0]->Type;
                    
                    if (strtoupper($currentType) !== strtoupper(preg_replace('/\s+/', '', $type))) {
                        DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` {$type}");
                        echo "[SUCCÈS] Colonne '{$table}.{$column}' mise à jour en {$type}.\n";
                    } else {
                        echo "[INFO] La colonne '{$table}.{$column}' est déjà du type {$type}.\n";
                    }
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de mettre à jour la colonne '{$table}.{$column}': " . $e->getMessage() . "\n";
                }
            }
        }
    }

    private function recreateForeignKeys(): void
    {
        echo "\n=== Étape 3: Recréation des contraintes ===\n";

        $foreignKeys = [
            'book_loans' => [
                'user_id' => [
                    'references' => 'users',
                    'on' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
                'book_id' => [
                    'references' => 'books',
                    'on' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
                'issued_by' => [
                    'references' => 'users',
                    'on' => 'id',
                    'onDelete' => 'SET NULL',
                    'onUpdate' => 'CASCADE',
                ],
            ],
            'book_requests' => [
                'student_id' => [
                    'references' => 'students',
                    'on' => 'user_id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
                'book_id' => [
                    'references' => 'books',
                    'on' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
                'approved_by' => [
                    'references' => 'users',
                    'on' => 'id',
                    'onDelete' => 'SET NULL',
                    'onUpdate' => 'CASCADE',
                ],
            ],
            // Ajoutez d'autres tables et contraintes au besoin
        ];

        foreach ($foreignKeys as $table => $constraints) {
            if (!Schema::hasTable($table)) {
                echo "[INFO] La table '{$table}' n'existe pas. Ignorée.\n";
                continue;
            }

            foreach ($constraints as $column => $constraint) {
                if (!Schema::hasColumn($table, $column)) {
                    echo "[INFO] La colonne '{$table}.{$column}' n'existe pas. Ignorée.\n";
                    continue;
                }

                $constraintName = "{$table}_{$column}_foreign";

                // Vérifier si la contrainte existe déjà
                $constraintExists = DB::select("
                    SELECT 1 FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE CONSTRAINT_SCHEMA = ? 
                    AND TABLE_NAME = ? 
                    AND CONSTRAINT_NAME = ?
                    AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                ", [DB::getDatabaseName(), $table, $constraintName]);

                if (!empty($constraintExists)) {
                    echo "[INFO] La contrainte '{$constraintName}' existe déjà. Ignorée.\n";
                    continue;
                }

                // Vérifier si la table de référence existe
                if (!Schema::hasTable($constraint['references'])) {
                    echo "[ERREUR] La table de référence '{$constraint['references']}' n'existe pas. Impossible de créer la contrainte '{$constraintName}'.\n";
                    continue;
                }

                // Vérifier si la colonne de référence existe
                $columnExists = DB::select("SHOW COLUMNS FROM `{$constraint['references']}` WHERE Field = '{$constraint['on']}'");
                if (empty($columnExists)) {
                    echo "[ERREUR] La colonne de référence '{$constraint['references']}.{$constraint['on']}' n'existe pas. Impossible de créer la contrainte '{$constraintName}'.\n";
                    continue;
                }

                try {
                    // Créer la contrainte
                    DB::statement("
                        ALTER TABLE `{$table}`
                        ADD CONSTRAINT `{$constraintName}`
                        FOREIGN KEY (`{$column}`)
                        REFERENCES `{$constraint['references']}` (`{$constraint['on']}`)
                        ON DELETE {$constraint['onDelete']}
                        ON UPDATE {$constraint['onUpdate']};
                    ");
                    
                    echo "[SUCCÈS] Contrainte '{$constraintName}' recréée.\n";
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de créer la contrainte '{$constraintName}': " . $e->getMessage() . "\n";
                }
            }
        }
    }

    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback pourrait causer des problèmes d'intégrité des données
    }
}
