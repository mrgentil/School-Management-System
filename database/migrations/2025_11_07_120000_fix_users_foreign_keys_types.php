<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Harmonisation des clés étrangères pointant vers users.id (INT UNSIGNED)
         */

        // === TABLE book_loans ===
        Schema::table('book_loans', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->unsignedBigInteger('user_id')->change();
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')
//                 ->references('id')
//                 ->on('users')
//                 ->onDelete('cascade');
        });
        // === TABLE book_reservations ===
        Schema::table('book_reservations', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->unsignedBigInteger('user_id')->change();
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')
//                 ->references('id')
//                 ->on('users')
//                 ->onDelete('cascade');
        });
        // === TABLE book_reviews ===
        Schema::table('book_reviews', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->unsignedBigInteger('user_id')->change();
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')
//                 ->references('id')
//                 ->on('users')
//                 ->onDelete('cascade');
        });
        // === TABLE messages ===
        Schema::table('messages', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'receiver_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->unsignedBigInteger('receiver_id')->change();
            // FK for column 'receiver_id' moved to final migration (auto-generated).
// Original: $table->foreign('receiver_id')
//                 ->references('id')
//                 ->on('users')
//                 ->onDelete('cascade');
        });
        // === TABLE pins ===
        Schema::table('pins', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->unsignedBigInteger('user_id')->change();
            // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')
//                 ->references('id')
//                 ->on('users')
//                 ->onDelete('cascade');
        });
        // === TABLE student_records ===
        Schema::table('student_records', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'my_parent_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->unsignedBigInteger('my_parent_id')->change();
            // FK for column 'my_parent_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_parent_id')
//                 ->references('id')
//                 ->on('users')
//                 ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Rétablit les anciennes versions si besoin
        Schema::table('book_loans', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->integer('user_id')->change();
        });

        Schema::table('book_reservations', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->integer('user_id')->change();
        });

        Schema::table('book_reviews', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->integer('user_id')->change();
        });

        Schema::table('messages', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'receiver_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->integer('receiver_id')->change();
        });

        Schema::table('pins', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->integer('user_id')->change();
        });

        Schema::table('student_records', function (Blueprint $table) {
            
                    // Safely drop foreign key if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_loans' AND COLUMN_NAME = 'my_parent_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_loans', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            $table->integer('my_parent_id')->change();
        });
    }
};
