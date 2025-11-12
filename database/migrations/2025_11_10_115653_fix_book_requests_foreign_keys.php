<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBookRequestsForeignKeys extends Migration
{
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Correction pour la colonne approved_by
            if (Schema::hasTable('book_requests') && Schema::hasColumn('book_requests', 'approved_by')) {
                // Supprimer la/les contrainte(s) existante(s) si elles existent sans Doctrine
                $constraints = DB::select("\n                    SELECT CONSTRAINT_NAME AS name\n                    FROM information_schema.KEY_COLUMN_USAGE\n                    WHERE TABLE_SCHEMA = DATABASE()\n                      AND TABLE_NAME = 'book_requests'\n                      AND COLUMN_NAME = 'approved_by'\n                      AND REFERENCED_TABLE_NAME IS NOT NULL\n                ");

                if (!empty($constraints)) {
                    Schema::table('book_requests', function ($table) use ($constraints) {
                        foreach ($constraints as $c) {
                            $table->dropForeign($c->name);
                        }
                    });
                }

                // Modifier le type de la colonne pour correspondre à users.id
                DB::statement('ALTER TABLE `book_requests` MODIFY COLUMN `approved_by` INT UNSIGNED NULL');

                // Recréer la contrainte de clé étrangère (gérée plus tard par les migrations finales)
                Schema::table('book_requests', function ($table) {
                    // Intentionnellement laissé vide: la recréation finale est centralisée
                });
            }
        } catch (\Exception $e) {
            echo "Erreur lors de la migration: " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down()
    {
        // Cette migration est irréversible car elle corrige un problème de schéma
    }
}