<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixForeignKeysComplete extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // 1. Supprimer toutes les contraintes problématiques
            $this->dropAllForeignKeys();

            // 2. Mettre à jour les types de colonnes
            $this->updateColumns();

            // 3. Recréer les contraintes
            $this->recreateForeignKeys();

            echo "\n=== Toutes les mises à jour ont été effectuées avec succès ===\n";

        } catch (\Exception $e) {
            echo "\n[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function dropAllForeignKeys(): void
    {
        $tables = ['book_loans', 'book_requests', 'students'];
        
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                echo "[INFO] La table '{$table}' n'existe pas.\n";
                continue;
            }

            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = ? 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
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

    private function updateColumns(): void
    {
        // Mettre à jour la colonne id de la table users si nécessaire
        $this->updateColumn('users', 'id', 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        // Mettre à jour la colonne id de la table books pour correspondre aux FKs BIGINT
        $this->updateColumn('books', 'id', 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        
        // Mettre à jour les colonnes de book_loans
        $this->updateColumn('book_loans', 'user_id', 'BIGINT UNSIGNED NOT NULL');
        $this->updateColumn('book_loans', 'book_id', 'BIGINT UNSIGNED NOT NULL');
        $this->updateColumn('book_loans', 'issued_by', 'BIGINT UNSIGNED NULL');
        $this->updateColumn('book_requests', 'approved_by', 'BIGINT UNSIGNED NULL');
        
        // Mettre à jour les colonnes de book_requests
        $this->updateColumn('book_requests', 'student_id', 'BIGINT UNSIGNED NOT NULL');
        $this->updateColumn('book_requests', 'book_id', 'BIGINT UNSIGNED NOT NULL');
        $this->updateColumn('book_requests', 'approved_by', 'BIGINT UNSIGNED NULL');
        
        // Mettre à jour la colonne user_id de la table students
        $this->updateColumn('students', 'user_id', 'BIGINT UNSIGNED NOT NULL');
        // S'assurer que student_records.user_id est nullable pour SET NULL
        $this->updateColumn('student_records', 'user_id', 'BIGINT UNSIGNED NULL');
    }

    private function updateColumn(string $table, string $column, string $type): void
    {
        if (!Schema::hasTable($table)) {
            echo "[INFO] La table '{$table}' n'existe pas.\n";
            return;
        }

        if (!Schema::hasColumn($table, $column)) {
            echo "[INFO] La colonne '{$table}.{$column}' n'existe pas.\n";
            return;
        }

        try {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` {$type}");
            echo "[SUCCÈS] Colonne '{$table}.{$column}' mise à jour en {$type}.\n";
        } catch (\Exception $e) {
            echo "[ERREUR] Impossible de mettre à jour la colonne '{$table}.{$column}': " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    private function recreateForeignKeys(): void
    {
        // Clés étrangères pour book_loans
        $this->addForeignKey('book_loans', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('book_loans', 'book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('book_loans', 'issued_by', 'users', 'id', 'SET NULL', 'CASCADE');
        
        // Clés étrangères pour book_requests
        $this->addForeignKey('book_requests', 'student_id', 'students', 'user_id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('book_requests', 'book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('book_requests', 'approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        
        // Clé étrangère pour students
        $this->addForeignKey('students', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    private function addForeignKey(
        string $table, 
        string $column, 
        string $referencedTable, 
        string $referencedColumn, 
        string $onDelete = 'RESTRICT', 
        string $onUpdate = 'CASCADE'
    ): void {
        if (!Schema::hasTable($table) || !Schema::hasTable($referencedTable)) {
            echo "[ERREUR] Une des tables n'existe pas.\n";
            return;
        }

        if (!Schema::hasColumn($table, $column)) {
            echo "[ERREUR] La colonne '{$table}.{$column}' n'existe pas.\n";
            return;
        }

        $constraintName = "{$table}_{$column}_foreign";
        
        try {
            DB::statement("
                ALTER TABLE `{$table}`
                ADD CONSTRAINT `{$constraintName}`
                FOREIGN KEY (`{$column}`)
                REFERENCES `{$referencedTable}` (`{$referencedColumn}`)
                ON DELETE {$onDelete}
                ON UPDATE {$onUpdate};
            ");

            echo "[SUCCÈS] Contrainte '{$constraintName}' créée avec succès.\n";
            
        } catch (\Exception $e) {
            echo "[ERREUR] Impossible de créer la contrainte '{$constraintName}': " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback pourrait causer des problèmes d'intégrité des données
    }
}
