<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBookLoansUserId extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            if (!Schema::hasTable('book_loans') || !Schema::hasTable('users')) {
                echo "[ERREUR] Les tables nécessaires n'existent pas.\n";
                return;
            }

            // 1. Vérifier et supprimer la contrainte existante
            $this->dropForeignKeyIfExists('book_loans', 'book_loans_user_id_foreign');

            // 2. Vérifier le type actuel de la colonne user_id
            $columnInfo = DB::select("SHOW COLUMNS FROM `book_loans` WHERE Field = 'user_id'");
            
            if (empty($columnInfo)) {
                echo "[ERREUR] La colonne 'user_id' n'existe pas dans la table 'book_loans'.\n";
                return;
            }

            $currentType = $columnInfo[0]->Type;
            $targetType = 'BIGINT UNSIGNED';
            $isNullable = $columnInfo[0]->Null === 'YES';
            $nullModifier = $isNullable ? 'NULL' : 'NOT NULL';

            // 3. Modifier le type de la colonne si nécessaire
            if (strtoupper($currentType) !== strtoupper(str_replace(' ', '', $targetType))) {
                DB::statement("ALTER TABLE `book_loans` MODIFY `user_id` {$targetType} {$nullModifier}");
                echo "[SUCCÈS] Colonne 'user_id' mise à jour en {$targetType} {$nullModifier}.\n";
            } else {
                echo "[INFO] La colonne 'user_id' est déjà du type {$targetType} {$nullModifier}.\n";
            }

            // 4. Vérifier le type de la colonne id dans la table users
            $usersIdInfo = DB::select("SHOW COLUMNS FROM `users` WHERE Field = 'id'");
            
            if (empty($usersIdInfo)) {
                echo "[ERREUR] Impossible de récupérer les informations de la colonne 'id' de la table 'users'.\n";
                return;
            }

            $usersIdType = $usersIdInfo[0]->Type;
            echo "[INFO] Type de la colonne 'users.id': {$usersIdType}\n";

            // 5. Recréer la contrainte
            $constraintName = 'book_loans_user_id_foreign';
            
            // Vérifier si la contrainte existe déjà
            $constraintExists = DB::select("
                SELECT 1 FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = ? 
                AND TABLE_NAME = 'book_loans' 
                AND CONSTRAINT_NAME = ?
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ", [DB::getDatabaseName(), $constraintName]);

            if (empty($constraintExists)) {
                DB::statement("
                    ALTER TABLE `book_loans`
                    ADD CONSTRAINT `{$constraintName}`
                    FOREIGN KEY (`user_id`)
                    REFERENCES `users` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE;
                ");
                echo "[SUCCÈS] Contrainte '{$constraintName}' recréée.\n";
            } else {
                echo "[INFO] La contrainte '{$constraintName}' existe déjà.\n";
            }

        } catch (\Exception $e) {
            echo "\n[ERREUR] " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraintName}`");
            echo "[SUCCÈS] Contrainte '{$constraintName}' supprimée.\n";
        } else {
            echo "[INFO] La contrainte '{$constraintName}' n'existe pas.\n";
        }
    }

    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback pourrait causer des problèmes d'intégrité des données
    }
}
