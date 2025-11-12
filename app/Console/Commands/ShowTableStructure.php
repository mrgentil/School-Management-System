<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowTableStructure extends Command
{
    protected $signature = 'table:structure {table} {--connection=mysql}';
    protected $description = 'Affiche la structure d\'une table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table = $this->argument('table');
        $connection = $this->option('connection');
        
        if (!Schema::connection($connection)->hasTable($table)) {
            $this->error("La table {$table} n'existe pas sur la connexion {$connection}.");
            return 1;
        }
        
        $columns = Schema::getColumnListing($table);
        $this->info("Structure de la table {$table}:");
        
        $headers = ['Colonne', 'Type', 'Null', 'Clé', 'Défaut', 'Extra'];
        $rows = [];
        
        foreach ($columns as $column) {
            try {
                $columnType = DB::connection($connection)
                    ->getDoctrineColumn($table, $column)
                    ->getType()
                    ->getName();
            } catch (\Exception $e) {
                $columnType = 'unknown';
            }
            
            $columnInfo = DB::selectOne(
                "SHOW COLUMNS FROM `{$table}` WHERE Field = ?", 
                [$column]
            );
            
            $columnType = $columnInfo->Type;
            
            $rows[] = [
                $column,
                $columnType,
                $columnInfo->Null,
                $columnInfo->Key,
                $columnInfo->Default ?? 'NULL',
                $columnInfo->Extra
            ];
        }
        
        $this->table($headers, $rows);
        
        // Afficher les clés étrangères
        $foreignKeys = DB::select("
            SELECT 
                COLUMN_NAME, 
                CONSTRAINT_NAME, 
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE 
                TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = '{$table}'
                AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        if (!empty($foreignKeys)) {
            $this->info("\nClés étrangères :");
            $foreignHeaders = ['Colonne', 'Contrainte', 'Table référencée', 'Colonne référencée'];
            $foreignRows = [];
            
            foreach ($foreignKeys as $fk) {
                $foreignRows[] = [
                    $fk->COLUMN_NAME,
                    $fk->CONSTRAINT_NAME,
                    $fk->REFERENCED_TABLE_NAME,
                    $fk->REFERENCED_COLUMN_NAME
                ];
            }
            
            $this->table($foreignHeaders, $foreignRows);
        }
        
        return 0;
    }
}
