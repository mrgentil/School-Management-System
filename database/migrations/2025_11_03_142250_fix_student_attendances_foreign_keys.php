<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixStudentAttendancesForeignKeys extends Migration
{
    public function up()
    {
        // Désactiver temporairement la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Supprimer toutes les contraintes de clés étrangères référençant users.id
        $tablesWithForeignKeys = [
            'sections' => ['teacher_id'],
            'student_records' => ['user_id', 'my_parent_id'],
            'book_requests' => ['student_id', 'approved_by'],
            'student_attendances' => ['student_id', 'marked_by'],
            'subjects' => ['teacher_id'],
            'assignment_submissions' => ['student_id'],
            'assignments' => ['teacher_id'],
            'book_loans' => ['user_id'],
            'book_reservations' => ['user_id'],
            'book_reviews' => ['user_id'],
            'exam_records' => ['student_id'],
            'marks' => ['student_id'],
            'message_recipients' => ['recipient_id'],
            'messages' => ['receiver_id', 'sender_id'],
            'notices' => ['created_by'],
            'payment_records' => ['student_id'],
            'pins' => ['student_id', 'user_id'],
            'promotions' => ['student_id'],
            'school_events' => ['created_by'],
            'staff_records' => ['user_id'],
            'students' => ['user_id'],
            'study_materials' => ['uploaded_by'],
            // Ajouter d'autres tables si nécessaire
        ];

        foreach ($tablesWithForeignKeys as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                foreach ($columns as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $constraintName = "{$tableName}_{$column}_foreign";
                        $exists = DB::select("SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'", [$tableName, $constraintName]);
                        if (!empty($exists)) {
                            DB::statement("ALTER TABLE `$tableName` DROP FOREIGN KEY `$constraintName`");
                        }
                    }
                }
            }
        }

        // Mettre à jour la colonne id de la table users en BIGINT UNSIGNED
        if (Schema::hasColumn('users', 'id')) {
            DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
        }

        // Mettre à jour les colonnes de clés étrangères dans les tables concernées
        $columnUpdates = [
            'sections' => ['teacher_id' => 'BIGINT UNSIGNED NULL'],
            // Rendre user_id NULL pour compatibilité avec ON DELETE SET NULL
            'student_records' => ['user_id' => 'BIGINT UNSIGNED NULL', 'my_parent_id' => 'BIGINT UNSIGNED NULL'],
            'book_requests' => ['student_id' => 'BIGINT UNSIGNED NOT NULL', 'approved_by' => 'BIGINT UNSIGNED NULL'],
            'student_attendances' => ['student_id' => 'BIGINT UNSIGNED NOT NULL', 'marked_by' => 'BIGINT UNSIGNED NOT NULL'],
            'subjects' => ['teacher_id' => 'BIGINT UNSIGNED NULL'],
            'assignment_submissions' => ['student_id' => 'BIGINT UNSIGNED NOT NULL'],
            'assignments' => ['teacher_id' => 'BIGINT UNSIGNED NULL'],
            'book_loans' => ['user_id' => 'BIGINT UNSIGNED NOT NULL'],
            'book_reservations' => ['user_id' => 'BIGINT UNSIGNED NOT NULL'],
            'book_reviews' => ['user_id' => 'BIGINT UNSIGNED NOT NULL'],
            'exam_records' => ['student_id' => 'BIGINT UNSIGNED NOT NULL'],
            'marks' => ['student_id' => 'BIGINT UNSIGNED NOT NULL'],
            'message_recipients' => ['recipient_id' => 'BIGINT UNSIGNED NOT NULL'],
            'messages' => ['receiver_id' => 'BIGINT UNSIGNED NOT NULL', 'sender_id' => 'BIGINT UNSIGNED NOT NULL'],
            'notices' => ['created_by' => 'BIGINT UNSIGNED NOT NULL'],
            'payment_records' => ['student_id' => 'BIGINT UNSIGNED NOT NULL'],
            'pins' => ['student_id' => 'BIGINT UNSIGNED NOT NULL', 'user_id' => 'BIGINT UNSIGNED NOT NULL'],
            'promotions' => ['student_id' => 'BIGINT UNSIGNED NOT NULL'],
            'school_events' => ['created_by' => 'BIGINT UNSIGNED NOT NULL'],
            'staff_records' => ['user_id' => 'BIGINT UNSIGNED NOT NULL'],
            'students' => ['user_id' => 'BIGINT UNSIGNED NOT NULL'],
            'study_materials' => ['uploaded_by' => 'BIGINT UNSIGNED NOT NULL'],
        ];

        foreach ($columnUpdates as $table => $columns) {
            if (Schema::hasTable($table)) {
                foreach ($columns as $column => $type) {
                    if (Schema::hasColumn($table, $column)) {
                        DB::statement("ALTER TABLE `$table` MODIFY `$column` $type;");
                    }
                }
            }
        }

        // Recréer les contraintes de clés étrangères
        Schema::table('sections', function (Blueprint $table) {
            // FK for column 'teacher_id' moved to final migration (auto-generated).
// Original: $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('student_records', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'my_parent_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_parent_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('book_requests', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'approved_by' moved to final migration (auto-generated).
// Original: $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('student_attendances', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'marked_by' moved to final migration (auto-generated).
// Original: $table->foreign('marked_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('subjects', function (Blueprint $table) {
            // FK for column 'teacher_id' moved to final migration (auto-generated).
// Original: $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('assignments', function (Blueprint $table) {
            // FK for column 'teacher_id' moved to final migration (auto-generated).
// Original: $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('book_loans', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('book_reservations', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('book_reviews', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('exam_records', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('marks', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('message_recipients', function (Blueprint $table) {
            // FK for column 'recipient_id' moved to final migration (auto-generated).
// Original: $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            // FK for column 'receiver_id' moved to final migration (auto-generated).
// Original: $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'sender_id' moved to final migration (auto-generated).
// Original: $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('notices', function (Blueprint $table) {
            // FK for column 'created_by' moved to final migration (auto-generated).
// Original: $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('payment_records', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('pins', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('promotions', function (Blueprint $table) {
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('school_events', function (Blueprint $table) {
            // FK for column 'created_by' moved to final migration (auto-generated).
// Original: $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('staff_records', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('study_materials', function (Blueprint $table) {
            // FK for column 'uploaded_by' moved to final migration (auto-generated).
// Original: $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Réactiver la vérification des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // Cette migration est à sens unique car elle corrige un problème d'intégrité des données
        // Un rollback pourrait causer des problèmes, donc nous laissons cette méthode vide
    }
}
