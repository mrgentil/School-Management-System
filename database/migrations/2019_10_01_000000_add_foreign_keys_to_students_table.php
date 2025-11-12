<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Vérifier si la colonne existe avant d'ajouter la contrainte
            if (Schema::hasColumn('students', 'user_id')) {
                // FK for column 'user_id' moved to final migration (auto-generated).
// Original: $table->foreign('user_id')
//                       ->references('id')
//                       ->on('users')
//                       ->onDelete('cascade');
   }
            
            // Vérifier si la colonne existe avant d'ajouter la contrainte
            if (Schema::hasColumn('students', 'class_id') && Schema::hasTable('my_classes')) {
                // FK for column 'class_id' moved to final migration (auto-generated).
// Original: $table->foreign('class_id')
//                       ->references('id')
//                       ->on('my_classes')
//                       ->onDelete('set null');
   }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                                    // Safely drop foreign key for column 'user_id' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('students', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            }
            
            if (Schema::hasColumn('students', 'class_id')) {
                                    // Safely drop foreign key for column 'class_id' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'class_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('students', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

            }
        });
    }
}
