<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableFirst extends Migration
{
    public function up()
    {
        // Désactiver temporairement les vérifications de clés étrangères
        Schema::disableForeignKeyConstraints();

        // 1. Mettre à jour la colonne id de users en BIGINT UNSIGNED si ce n'est pas déjà le cas
        if (Schema::hasColumn('users', 'id')) {
            // Vérifier si la colonne est déjà en BIGINT UNSIGNED
            $columns = DB::select("SHOW COLUMNS FROM users WHERE Field = 'id'");
            
            $column = $columns[0];
            if (strpos(strtolower($column->Type), 'bigint') === false) {
                DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            }
        }

        // Réactiver les vérifications de clés étrangères
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        // Dans le down(), on ne fait généralement rien pour ce type de migration
        // car le rollback se fera via les autres migrations
    }
}
