<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InspectTables extends Migration
{
    public function up(): void
    {
        $this->inspectTable('book_loans');
        $this->inspectTable('users');
    }

    private function inspectTable(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            echo "[ERREUR] La table '{$tableName}' n'existe pas.\n";
            return;
        }

        echo "\n=== Structure de la table '{$tableName}' ===\n";
        
        // Afficher les colonnes
        $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");
        
        echo "\nColonnes :\n";
        foreach ($columns as $column) {
            echo "- {$column->Field} : {$column->Type} ";
            echo $column->Null === 'YES' ? 'NULL' : 'NOT NULL';
            echo $column->Key === 'PRI' ? ' PRIMARY KEY' : '';
            echo $column->Extra ? ' ' . $column->Extra : '';
            echo "\n";
        }

        // Afficher les contraintes de clé étrangère
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
        ", [DB::getDatabaseName(), $tableName]);

        if (!empty($constraints)) {
            echo "\nContraintes de clé étrangère :\n";
            foreach ($constraints as $constraint) {
                echo "- {$constraint->CONSTRAINT_NAME} : {$constraint->COLUMN_NAME} -> {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}\n";
            }
        } else {
            echo "\nAucune contrainte de clé étrangère trouvée.\n";
        }
        
        echo str_repeat('=', 50) . "\n";
    }

    public function down(): void
    {
        // Cette migration est en lecture seule, pas de rollback nécessaire
    }
}
