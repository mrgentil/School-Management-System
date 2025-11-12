<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixAllForeignKeysToUsers extends Migration
{
    public function up(): void
    {
        // Désactiver temporairement les vérifications de clés étrangères
        Schema::disableForeignKeyConstraints();

        // Fonction pour mettre à jour une colonne de clé étrangère
        $updateForeignKey = function($tableName, $columnName, $isNullable = false) {
            // Vérifier si la table et la colonne existent
            if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, $columnName)) {
                return;
            }

            // Supprimer la contrainte de clé étrangère si elle existe
            $constraintName = $this->getForeignKeyName($tableName, $columnName);
            if (!$constraintName && $tableName == 'student_records' && $columnName == 'user_id') {
                $constraintName = 'fk_student_records_user_id';
            }
            if ($constraintName) {
                try {
                    DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`");
                } catch (\Exception $e) {
                    // Ignore if drop fails
                }
            }

            // Modifier le type de la colonne
            $nullModifier = $isNullable ? ' NULL' : ' NOT NULL';

            // Vérifier si la colonne est déjà en BIGINT UNSIGNED
            $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}` WHERE Field = '{$columnName}'")[0] ?? null;

            if ($columnInfo) {
                $isBigInt = strpos(strtolower($columnInfo->Type), 'bigint') !== false;
                $isNullableCol = $columnInfo->Null === 'YES';

                // Forcer nullable pour student_records.user_id si SET NULL
                if ($tableName === 'student_records' && $columnName === 'user_id') {
                    $isNullable = true;
                    $nullModifier = ' NULL';
                }

                if (!$isBigInt || ($isNullableCol !== $isNullable)) {
                    DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$columnName}` BIGINT UNSIGNED{$nullModifier}");
                }
            }

            // Recréer la contrainte de clé étrangère si elle n'existe pas déjà
            if (!$this->foreignKeyExists($tableName, $columnName)) {
                Schema::table($tableName, function (Blueprint $table) use ($columnName, $isNullable) {
                    $foreign = $table->foreign($columnName)
                        ->references('id')
                        ->on('users')
                        ->onUpdate('cascade');

                    if ($isNullable) {
                        $foreign->onDelete('set null');
                    } else {
                        $foreign->onDelete('cascade');
                    }
                });
            }
        };

        // Mettre à jour les colonnes de clé étrangère
        $tables = [
            'book_loans' => ['user_id' => false],
            'book_reservations' => ['user_id' => false],
            'book_reviews' => ['user_id' => false],
            'messages' => ['receiver_id' => true],
            'notifications' => ['user_id' => false],
            'student_attendances' => ['marked_by' => false],
            'sections' => ['teacher_id' => true],
            'pins' => ['user_id' => false],
            'book_requests' => ['user_id' => false],
            'assignment_submissions' => ['user_id' => false],
            'payments' => ['user_id' => false],
            'payment_records' => ['user_id' => false, 'created_by' => false],
            'receipts' => ['user_id' => false, 'recorded_by' => false],
            'student_records' => ['user_id' => true, 'my_parent_id' => true]
        ];

        foreach ($tables as $table => $columns) {
            foreach ($columns as $column => $nullable) {
                $updateForeignKey($table, $column, $nullable);
            }
        }

        // Réactiver les vérifications de clés étrangères
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Vérifie si une contrainte de clé étrangère existe déjà
     */
    private function foreignKeyExists($tableName, $columnName)
    {
        $databaseName = DB::getDatabaseName();
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '{$databaseName}'
            AND TABLE_NAME = '{$tableName}'
            AND COLUMN_NAME = '{$columnName}'
            AND REFERENCED_TABLE_NAME = 'users'
            AND REFERENCED_COLUMN_NAME = 'id'
        ");

        return !empty($constraints);
    }

    /**
     * Obtenir le nom de la contrainte de clé étrangère
     */
    private function getForeignKeyName($tableName, $columnName)
    {
        $databaseName = DB::getDatabaseName();
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '{$databaseName}'
            AND TABLE_NAME = '{$tableName}'
            AND COLUMN_NAME = '{$columnName}'
            AND REFERENCED_TABLE_NAME = 'users'
            AND REFERENCED_COLUMN_NAME = 'id'
        ");

        return !empty($constraints) ? $constraints[0]->CONSTRAINT_NAME : null;
    }

    public function down(): void
    {
        // Cette méthode est laissée vide intentionnellement
        // car le rollback sera géré par les autres migrations
    }
}
