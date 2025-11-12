<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'table:structure {table} {--connection=mysql}';
    protected $description = 'Affiche la structure d\'une table spécifique';

    public function handle()
    {
        $table = $this->argument('table');
        $connection = $this->option('connection');

        if (!Schema::connection($connection)->hasTable($table)) {
            $this->error("La table {$table} n'existe pas !");
            return 1;
        }

        $columns = Schema::connection($connection)->getColumnListing($table);
        $this->info("Colonnes de la table {$table} :");
        
        foreach ($columns as $column) {
            $type = DB::connection($connection)
                     ->getDoctrineColumn($table, $column)
                     ->getType()
                     ->getName();
            
            $this->line("- {$column} : {$type}");
        }

        return 0;
    }
}
