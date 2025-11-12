<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableIdToBigIncrements extends Migration
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
            'student_records' => ['user_id', 'my_parent_id'],
            'book_requests' => ['approved_by'],

            'subjects' => ['teacher_id'],
            'assignment_submissions' => ['student_id'],
            'assignments' => ['teacher_id'],
            'book_loans' => ['user_id', 'issued_by'],
            'book_reservations' => ['user_id'],
            'book_reviews' => ['user_id'],
            'exam_records' => ['student_id'],
            'marks' => ['student_id', 'teacher_id'],
            'message_recipients' => ['recipient_id'],
            'messages' => ['sender_id', 'receiver_id'],
            'notices' => ['created_by'],
            'payment_records' => ['student_id'],
            'pins' => ['student_id', 'user_id'],
            'promotions' => ['student_id'],
            'school_events' => ['created_by'],
            'staff_records' => ['user_id'],
            // 'students' => ['user_id'],
            'study_materials' => ['uploaded_by'],
        ];
        // Supprimer les clés étrangères existantes et modifier les colonnes FK en BIGINT
        foreach ($tables as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    $constraintName = "{$tableName}_{$column}_foreign";

                    // Vérifier si la contrainte existe
                    $exists = DB::select("SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'", [$tableName, $constraintName]);
                    if (!empty($exists)) {
                        DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`;");
                    }

                    // Vérifier si la colonne est nullable
                    $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}` WHERE Field = ?", [$column]);
                    $isNullable = $columnInfo[0]->Null === 'YES';
                    $onDelete = in_array($column, ['teacher_id', 'approved_by', 'marked_by', 'issued_by', 'created_by', 'uploaded_by']) ? 'SET NULL' : 'CASCADE';
                    $nullClause = ($isNullable || $onDelete === 'SET NULL') ? 'NULL' : 'NOT NULL';

                    // Modifier la colonne en BIGINT UNSIGNED
                    DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$column}` BIGINT UNSIGNED {$nullClause};");
                }
            }
        }

        // Changer users.id en BIGINT UNSIGNED AUTO_INCREMENT
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Drop the foreign key from students table
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'user_id')) {
            $constraintName = "students_user_id_foreign";
            $exists = DB::select("SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'", [$constraintName]);
            if (!empty($exists)) {
                DB::statement("ALTER TABLE `students` DROP FOREIGN KEY `{$constraintName}`;");
            }
        }
        if (Schema::hasColumn('users', 'id')) {
            DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Recréer les clés étrangères
        foreach ($tables as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    $constraintName = "{$tableName}_{$column}_foreign";

                    // Cas spécial pour book_requests.etudiant_id qui doit référencer students.user_id
                    if ($tableName === 'book_requests' && $column === 'etudiant_id') {
                        // Vérifier si la table students et la colonne user_id existent
                        $studentsTableExists = DB::select("SHOW TABLES LIKE 'students'");
                        $userIdColumnExists = $studentsTableExists ? DB::select("SHOW COLUMNS FROM `students` WHERE Field = 'user_id'") : [];

                        if (!empty($studentsTableExists) && !empty($userIdColumnExists)) {
                            DB::statement("
                                ALTER TABLE `book_requests`
                                ADD CONSTRAINT `book_requests_etudiant_id_foreign`
                                FOREIGN KEY (`etudiant_id`)
                                REFERENCES `students` (`user_id`)
                                ON DELETE CASCADE
                                ON UPDATE CASCADE;
                            ");
                            continue; // Passer à la colonne suivante
                        }
                    }

                    // Pour toutes les autres colonnes, référencer users.id
                    $onDelete = in_array($column, ['teacher_id', 'approved_by', 'marked_by', 'issued_by', 'created_by', 'uploaded_by']) ? 'SET NULL' : 'CASCADE';

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
}
