<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateAllForeignKeysToUsers extends Migration
{
    public function up()
    {
        // Désactiver temporairement la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Mettre à jour la colonne id de la table users en BIGINT UNSIGNED
        if (Schema::hasColumn('users', 'id')) {
            DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
        }

        // 2. Liste des tables avec des clés étrangères vers users.id
        // Séparer les colonnes nullable et non-nullable
        $nullableColumns = [
            'sections' => ['teacher_id'],
            'exams' => ['teacher_id'],
            'grades' => ['teacher_id'],
            'teacher_subjects' => ['teacher_id'],
            'timetables' => ['teacher_id'],
            'book_issues' => ['issued_by'],
            'fees' => ['recorded_by'],
            'fee_payments' => ['recorded_by'],
            'expenses' => ['recorded_by'],
            'payrolls' => ['paid_by'],
            'student_notes' => ['created_by'],
            'student_remarks' => ['created_by'],
            'student_guardian_relations' => ['guardian_id'],
            'student_parents' => ['parent_id'],
        ];

        $notNullableColumns = [
            'student_attendances' => ['student_id', 'marked_by'],
            'notices' => ['created_by'],
            'student_guardians' => ['guardian_id'],
            'book_issues' => ['user_id'],
            'fees' => ['student_id'],
            'fee_payments' => ['student_id'],
            'payrolls' => ['employee_id'],
            'student_documents' => ['student_id'],
            'student_notes' => ['student_id'],
            'student_remarks' => ['student_id'],
            'student_guardian_relations' => ['student_id'],
            'student_parents' => ['student_id'],
        ];

        // 3. Fonction helper pour mettre à jour les colonnes et contraintes
        $updateColumns = function($tableName, $columns, $isNullable = false) {
            if (!Schema::hasTable($tableName)) {
                return;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    // Mettre à jour la colonne en BIGINT UNSIGNED
                    $nullability = $isNullable ? 'NULL' : 'NOT NULL';
                    DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$column}` BIGINT UNSIGNED {$nullability};");

                    // Recréer la contrainte de clé étrangère
                    $constraintName = "{$tableName}_{$column}_foreign";

                    // Supprimer la contrainte existante si elle existe
                    $exists = DB::select("SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'", [$tableName, $constraintName]);
                    if (!empty($exists)) {
                        DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`;");
                    }

                    // Recréer la contrainte
                    $onDelete = $isNullable ? 'SET NULL' : 'CASCADE';
                    DB::statement("
                        ALTER TABLE `{$tableName}`
                        ADD CONSTRAINT `{$constraintName}`
                        FOREIGN KEY (`{$column}`)
                        REFERENCES `users` (`id`)
                        ON DELETE {$onDelete}
                        ON UPDATE CASCADE;
                    ");
                }
            }
        };

        // Mettre à jour les colonnes nullable
        foreach ($nullableColumns as $tableName => $columns) {
            $updateColumns($tableName, $columns, true);
        }

        // Mettre à jour les colonnes non-nullable
        foreach ($notNullableColumns as $tableName => $columns) {
            $updateColumns($tableName, $columns, false);
        }

        // Réactiver la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // Cette migration est à sens unique car elle corrige des problèmes d'intégrité des données
        // Un rollback pourrait causer des problèmes, donc nous laissons cette méthode vide
    }
}
