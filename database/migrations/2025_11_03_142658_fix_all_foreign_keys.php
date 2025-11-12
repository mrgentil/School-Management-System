<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Désactiver temporairement la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. D'abord, obtenir la liste de toutes les contraintes de clé étrangère qui pointent vers users.id
        $foreignKeys = DB::select("
            SELECT 
                TABLE_NAME, 
                COLUMN_NAME, 
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE 
                REFERENCED_TABLE_SCHEMA = ? 
                AND REFERENCED_TABLE_NAME = 'users' 
                AND REFERENCED_COLUMN_NAME = 'id'
        ", [DB::getDatabaseName()]);

        // 2. Supprimer toutes les contraintes de clé étrangère
        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`;");
            } catch (\Exception $e) {
                // Ignorer les erreurs si la contrainte n'existe pas
                continue;
            }
        }

        // 3. Modifier le type de la colonne id dans la table users
        DB::statement('ALTER TABLE `users` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');

        // 4. Mettre à jour les colonnes dans les autres tables
        foreach ($foreignKeys as $fk) {
            if (Schema::hasTable($fk->TABLE_NAME) && Schema::hasColumn($fk->TABLE_NAME, $fk->COLUMN_NAME)) {
                // Modifier la colonne pour qu'elle corresponde au nouveau type
                DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` MODIFY `{$fk->COLUMN_NAME}` BIGINT UNSIGNED NOT NULL;");
                
                // Recréer la contrainte de clé étrangère
                DB::statement("
                    ALTER TABLE `{$fk->TABLE_NAME}`
                    ADD CONSTRAINT `{$fk->CONSTRAINT_NAME}`
                    FOREIGN KEY (`{$fk->COLUMN_NAME}`)
                    REFERENCES `users` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE;
                ");
            }
        }

        // 5. Réactiver la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // Cette migration est à sens unique car elle corrige des problèmes d'intégrité des données
        // Un rollback pourrait causer des problèmes, donc nous laissons cette méthode vide
    }
};
