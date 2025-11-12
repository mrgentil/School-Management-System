<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixForeignKeysFinal extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->fixBookLoansTable();
            $this->fixBookRequestsTable();
            // Ajoutez d'autres appels de méthode pour d'autres tables si nécessaire
            
            echo "\n=== Toutes les clés étrangères ont été corrigées avec succès ===\n";
            
        } catch (\Exception $e) {
            echo "\n[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function fixBookLoansTable(): void
    {
        if (!Schema::hasTable('book_loans')) {
            echo "[INFO] La table 'book_loans' n'existe pas. Ignorée.\n";
            return;
        }

        $this->fixForeignKey('book_loans', 'user_id', 'users', 'id');
        $this->fixForeignKey('book_loans', 'book_id', 'books', 'id');
        $this->fixForeignKey('book_loans', 'issued_by', 'users', 'id', true);
    }

    private function fixBookRequestsTable(): void
    {
        if (!Schema::hasTable('book_requests')) {
            echo "[INFO] La table 'book_requests' n'existe pas. Ignorée.\n";
            return;
        }

        $this->fixForeignKey('book_requests', 'student_id', 'students', 'user_id');
        $this->fixForeignKey('book_requests', 'book_id', 'books', 'id');
        $this->fixForeignKey('book_requests', 'approved_by', 'users', 'id', true);
    }

    private function fixForeignKey(string $table, string $column, string $references, string $on, bool $nullable = false): void
    {
        if (!Schema::hasColumn($table, $column)) {
            echo "[INFO] La colonne '{$table}.{$column}' n'existe pas. Ignorée.\n";
            return;
        }

        $constraintName = "{$table}_{$column}_foreign";
        
        // 1. Supprimer la contrainte existante si elle existe
        $constraintExists = DB::select("
            SELECT 1 FROM information_schema.TABLE_CONSTRAINTS 
            WHERE CONSTRAINT_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ?
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [DB::getDatabaseName(), $table, $constraintName]);

        if (!empty($constraintExists)) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraintName}`");
            echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée.\n";
        }

        // 2. Mettre à jour le type de la colonne
        $columnType = $nullable ? 'BIGINT UNSIGNED NULL' : 'BIGINT UNSIGNED NOT NULL';
        
        try {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` {$columnType}");
            echo "[SUCCÈS] Colonne '{$table}.{$column}' mise à jour en {$columnType}.\n";
        } catch (\Exception $e) {
            echo "[INFO] La colonne '{$table}.{$column}' n'a pas pu être mise à jour : " . $e->getMessage() . "\n";
        }

        // 3. Vérifier si la table de référence existe
        if (!Schema::hasTable($references)) {
            echo "[ERREUR] La table de référence '{$references}' n'existe pas. Impossible de créer la contrainte.\n";
            return;
        }

        // 4. Vérifier si la colonne de référence existe
        $columnExists = DB::select("SHOW COLUMNS FROM `{$references}` WHERE Field = '{$on}'");
        if (empty($columnExists)) {
            echo "[ERREUR] La colonne de référence '{$references}.{$on}' n'existe pas. Impossible de créer la contrainte.\n";
            return;
        }

        // 5. Recréer la contrainte
        try {
            $onDelete = $nullable ? 'SET NULL' : 'CASCADE';
            
            DB::statement("
                ALTER TABLE `{$table}`
                ADD CONSTRAINT `{$constraintName}`
                FOREIGN KEY (`{$column}`)
                REFERENCES `{$references}` (`{$on}`)
                ON DELETE {$onDelete}
                ON UPDATE CASCADE;
            ");
            
            echo "[SUCCÈS] Contrainte '{$constraintName}' recréée.\n";
        } catch (\Exception $e) {
            echo "[ERREUR] Impossible de créer la contrainte '{$constraintName}': " . $e->getMessage() . "\n";
        }
    }

    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback pourrait causer des problèmes d'intégrité des données
    }
}
