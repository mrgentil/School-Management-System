<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixForeignKeyIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Désactiver temporairement la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Liste des tables avec des clés étrangères vers users.id
        $tables = [
            'sections' => ['teacher_id'],
            'student_records' => ['user_id'],
            'book_requests' => ['student_id', 'approved_by'],
            'student_attendances' => ['student_id', 'marked_by'],
            'subjects' => ['teacher_id'],
            'assignment_submissions' => ['student_id'],
            'assignments' => ['teacher_id'],
            'book_loans' => ['student_id', 'issued_by'],
            'book_reservations' => ['student_id'],
            'book_reviews' => ['student_id'],
            'exam_records' => ['student_id'],
            'marks' => ['student_id', 'teacher_id'],
            'message_recipients' => ['recipient_id'],
            'messages' => ['sender_id'],
            'notices' => ['created_by'],
            'payment_records' => ['student_id'],
            'pins' => ['student_id'],
            'promotions' => ['student_id'],
            'school_events' => ['created_by'],
            'staff_records' => ['user_id'],
            'students' => ['user_id'],
            'study_materials' => ['uploaded_by'],
        ];

        // Supprimer les clés étrangères existantes
        foreach ($tables as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    $constraintName = $this->getForeignKeyName($tableName, $column);
                    
                    // Vérifier si la contrainte existe
                    if ($constraintName) {
                        DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`;");
                        // Modifier la colonne pour qu'elle corresponde au type de users.id
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$column}` BIGINT UNSIGNED NULL;");
                    }
                }
            }
        }

        // Modifier le type de la colonne id de la table users
        if (Schema::hasTable('users')) {
            DB::statement('ALTER TABLE `users` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
        }

        // Recréer les clés étrangères
        foreach ($tables as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    $constraintName = "{$tableName}_{$column}_foreign";
                    
                    // Déterminer l'action onDelete basée sur la colonne
                    $onDelete = in_array($column, ['teacher_id', 'approved_by', 'marked_by', 'issued_by', 'created_by', 'uploaded_by']) ? 'SET NULL' : 'CASCADE';
                    
                    // Vérifier si la colonne peut être nulle
                    $nullable = in_array($column, ['teacher_id', 'approved_by', 'marked_by', 'issued_by', 'created_by', 'uploaded_by']) ? 'NULL' : 'NOT NULL';

                    // Ensure the column is nullable if ON DELETE is SET NULL
                    if (stripos($onDelete, 'SET NULL') !== false) {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$column}` BIGINT UNSIGNED NULL;");
                    }

                    // Ajouter la contrainte de clé étrangère
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
        }

        // Réactiver la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Cette migration est à sens unique car elle corrige des problèmes d'intégrité des données
        // Un rollback pourrait causer des problèmes, donc nous laissons cette méthode vide
    }

    /**
     * Get the foreign key constraint name for a column
     *
     * @param string $table
     * @param string $column
     * @return string|null
     */
    private function getForeignKeyName($table, $column)
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ? 
            AND COLUMN_NAME = ? 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        return !empty($result) ? $result[0]->CONSTRAINT_NAME : null;
    }
}
