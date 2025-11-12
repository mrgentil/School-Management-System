<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixAllForeignKeysFinal extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // 1. Mettre à jour les colonnes dans book_loans
            $this->updateColumnType('book_loans', 'user_id', 'BIGINT UNSIGNED NOT NULL');
            $this->updateColumnType('book_loans', 'book_id', 'BIGINT UNSIGNED NOT NULL');
            $this->updateColumnType('book_loans', 'issued_by', 'BIGINT UNSIGNED NULL');

            // 2. Mettre à jour la colonne id dans users si nécessaire
            $this->updateColumnType('users', 'id', 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT', true);
            // 2b. Mettre à jour la colonne id dans books pour correspondre à BIGINT UNSIGNED
            $this->updateColumnType('books', 'id', 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT', true);

            // 3. Recréer les contraintes
            $this->recreateForeignKey(
                'book_loans', 
                'user_id', 
                'users', 
                'id', 
                'CASCADE', 
                'CASCADE'
            );

            $this->recreateForeignKey(
                'book_loans', 
                'book_id', 
                'books', 
                'id', 
                'CASCADE', 
                'CASCADE'
            );

            $this->recreateForeignKey(
                'book_loans', 
                'issued_by', 
                'users', 
                'id', 
                'SET NULL', 
                'CASCADE',
                true
            );

            echo "\n=== Toutes les mises à jour ont été effectuées avec succès ===\n";

        } catch (\Exception $e) {
            echo "\n[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function updateColumnType(string $table, string $column, string $type, bool $isPrimaryKey = false): void
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
            // Pour la clé primaire, on utilise une syntaxe spéciale
            if ($isPrimaryKey) {
                $sql = "ALTER TABLE `{$table}` MODIFY `{$column}` {$type} AUTO_INCREMENT";
            } else {
                $sql = "ALTER TABLE `{$table}` MODIFY `{$column}` {$type}";
            }
            
            DB::statement($sql);
            echo "[SUCCÈS] Colonne '{$table}.{$column}' mise à jour en {$type}.\n";
            
        } catch (\Exception $e) {
            echo "[ERREUR] Impossible de mettre à jour la colonne '{$table}.{$column}': " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    private function recreateForeignKey(
        string $table, 
        string $column, 
        string $referencedTable, 
        string $referencedColumn, 
        string $onDelete = 'RESTRICT', 
        string $onUpdate = 'CASCADE',
        bool $nullable = false
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
            // Supprimer la contrainte existante si elle existe
            $this->dropForeignKeyIfExists($table, $constraintName);

            // S'assurer que la colonne est nullable si nécessaire
            if ($nullable) {
                $this->updateColumnType($table, $column, 'BIGINT UNSIGNED NULL');
            }

            // Créer la nouvelle contrainte
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

    private function dropForeignKeyIfExists(string $table, string $constraintName): void
    {
        $constraintExists = DB::select("
            SELECT 1 FROM information_schema.TABLE_CONSTRAINTS 
            WHERE CONSTRAINT_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ?
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [DB::getDatabaseName(), $table, $constraintName]);

        if (!empty($constraintExists)) {
            try {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraintName}`");
                echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée.\n";
            } catch (\Exception $e) {
                echo "[ERREUR] Impossible de supprimer la contrainte '{$constraintName}': " . $e->getMessage() . "\n";
            }
        }
    }

    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback pourrait causer des problèmes d'intégrité des données
    }
}
