<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixRemainingForeignKeys extends Migration
{
    public function up(): void
    {
        // Désactiver temporairement les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // 1. Corriger les colonnes qui doivent être NULL
            $this->fixNullableColumns();
            
            // 2. Corriger les incompatibilités de type
            $this->fixTypeIncompatibilities();
            
            // 3. Recréer les contraintes de clé étrangère manquantes
            $this->recreateMissingForeignKeys();
            
            echo "\n=== Correction des problèmes restants terminée avec succès ===\n";
            
        } catch (\Exception $e) {
            echo "\n=== ERREUR LORS DE LA MIGRATION ===\n";
            echo "Erreur: " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            // Réactiver les vérifications de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
    
    /**
     * Rend les colonnes problématiques NULL si nécessaire
     */
    protected function fixNullableColumns(): void
    {
        echo "\n=== Correction des colonnes NULL ===\n";
        
        $tables = [
            'messages' => ['receiver_id'],
            'subjects' => ['teacher_id'],
            'assignments' => ['teacher_id']
        ];
        
        foreach ($tables as $table => $columns) {
            if (!Schema::hasTable($table)) {
                echo "[IGNORÉ] La table '{$table}' n'existe pas.\n";
                continue;
            }
            
            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    echo "[IGNORÉ] La colonne '{$column}' n'existe pas dans la table '{$table}'.\n";
                    continue;
                }
                
                try {
                    // Vérifier si la colonne est déjà nullable
                    $columnInfo = DB::select("SHOW COLUMNS FROM `{$table}` WHERE Field = '{$column}'")[0] ?? null;
                    
                    if ($columnInfo && $columnInfo->Null === 'NO') {
                        // Rendre la colonne nullable
                        DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` BIGINT UNSIGNED NULL");
                        echo "[SUCCÈS] Colonne '{$column}' de la table '{$table}' rendue nullable.\n";
                    } else {
                        echo "[DÉJÀ FAIT] La colonne '{$column}' de la table '{$table}' est déjà nullable.\n";
                    }
                } catch (\Exception $e) {
                    echo "[ERREUR] Impossible de rendre la colonne '{$column}' de la table '{$table}' nullable: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    /**
     * Corrige les incompatibilités de type
     */
    protected function fixTypeIncompatibilities(): void
    {
        echo "\n=== Correction des incompatibilités de type ===\n";
        
        // 1. Vérifier et corriger la table students.user_id
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'user_id')) {
            try {
                // Vérifier le type actuel de la colonne
                $columnInfo = DB::select("SHOW COLUMNS FROM `students` WHERE Field = 'user_id'")[0] ?? null;
                
                if ($columnInfo) {
                    $isNullable = $columnInfo->Null === 'YES' ? 'NULL' : 'NOT NULL';
                    
                    // Vérifier si la colonne est déjà en BIGINT UNSIGNED
                    if (strpos(strtolower($columnInfo->Type), 'bigint') === false) {
                        // Mettre à jour le type de la colonne
                        DB::statement("ALTER TABLE `students` MODIFY `user_id` BIGINT UNSIGNED {$isNullable}");
                        echo "[SUCCÈS] Colonne 'user_id' de la table 'students' mise à jour en BIGINT UNSIGNED.\n";
                    } else {
                        echo "[DÉJÀ FAIT] La colonne 'user_id' de la table 'students' est déjà de type BIGINT UNSIGNED.\n";
                    }
                }
            } catch (\Exception $e) {
                echo "[ERREUR] Impossible de mettre à jour la colonne 'user_id' de la table 'students': " . $e->getMessage() . "\n";
            }
        }
        
        // 2. Vérifier et corriger la table book_requests.student_id
        if (Schema::hasTable('book_requests') && Schema::hasColumn('book_requests', 'student_id')) {
            try {
                $columnInfo = DB::select("SHOW COLUMNS FROM `book_requests` WHERE Field = 'student_id'")[0] ?? null;
                
                if ($columnInfo) {
                    $isNullable = $columnInfo->Null === 'YES' ? 'NULL' : 'NOT NULL';
                    
                    if (strpos(strtolower($columnInfo->Type), 'bigint') === false) {
                        DB::statement("ALTER TABLE `book_requests` MODIFY `student_id` BIGINT UNSIGNED {$isNullable}");
                        echo "[SUCCÈS] Colonne 'student_id' de la table 'book_requests' mise à jour en BIGINT UNSIGNED.\n";
                    } else {
                        echo "[DÉJÀ FAIT] La colonne 'student_id' de la table 'book_requests' est déjà de type BIGINT UNSIGNED.\n";
                    }
                }
            } catch (\Exception $e) {
                echo "[ERREUR] Impossible de mettre à jour la colonne 'student_id' de la table 'book_requests': " . $e->getMessage() . "\n";
            }
        }
    }
    
    /**
     * Recrée les contraintes de clé étrangère manquantes
     */
    protected function recreateMissingForeignKeys(): void
    {
        echo "\n=== Recréation des contraintes manquantes ===\n";
        
        $constraints = [
            // Table: messages
            [
                'table' => 'messages',
                'column' => 'receiver_id',
                'onDelete' => 'SET NULL',
                'onUpdate' => 'CASCADE'
            ],
            
            // Table: subjects
            [
                'table' => 'subjects',
                'column' => 'teacher_id',
                'onDelete' => 'SET NULL',
                'onUpdate' => 'CASCADE'
            ],
            
            // Table: assignments
            [
                'table' => 'assignments',
                'column' => 'teacher_id',
                'onDelete' => 'SET NULL',
                'onUpdate' => 'CASCADE'
            ],
            
            // Table: students
            [
                'table' => 'students',
                'column' => 'user_id',
                'onDelete' => 'CASCADE',
                'onUpdate' => 'CASCADE'
            ]
        ];
        
        foreach ($constraints as $constraint) {
            $table = $constraint['table'];
            $column = $constraint['column'];
            $constraintName = "fk_{$table}_{$column}";
            
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                echo "[IGNORÉ] La table '{$table}' ou la colonne '{$column}' n'existe pas.\n";
                continue;
            }
            
            // Vérifier si la contrainte existe déjà
            if ($this->foreignKeyExists($table, $column)) {
                echo "[DÉJÀ FAIT] La contrainte pour '{$column}' dans la table '{$table}' existe déjà.\n";
                continue;
            }
            
            try {
                // Créer la contrainte
                DB::statement("\n                    ALTER TABLE `{$table}`\n                    ADD CONSTRAINT `{$constraintName}`\n                    FOREIGN KEY (`{$column}`)\n                    REFERENCES `users` (`id`)\n                    ON DELETE {$constraint['onDelete']}\n                    ON UPDATE {$constraint['onUpdate']}\n                
                ");
                echo "[SUCCÈS] Contrainte '{$constraintName}' recréée pour la colonne '{$column}' dans la table '{$table}'.\n";
                
            } catch (\Exception $e) {
                echo "[ERREUR] Impossible de recréer la contrainte pour '{$column}' dans la table '{$table}': " . $e->getMessage() . "\n";
            }
        }
    }
    
    /**
     * Vérifie si une contrainte de clé étrangère existe déjà
     */
    protected function foreignKeyExists($tableName, $columnName): bool
    {
        $databaseName = DB::getDatabaseName();
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = '{$databaseName}'
            AND TABLE_NAME = '{$tableName}'
            AND COLUMN_NAME = '{$columnName}'
            AND REFERENCED_TABLE_NAME = 'users'
            AND REFERENCED_COLUMN_NAME = 'id'
        ");
        
        return !empty($constraints);
    }
    
    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback sera géré par les autres migrations
    }
}
