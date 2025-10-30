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
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
            
            // Vérifier si la colonne existe avant d'ajouter la contrainte
            if (Schema::hasColumn('students', 'class_id') && Schema::hasTable('my_classes')) {
                $table->foreign('class_id')
                      ->references('id')
                      ->on('my_classes')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
            
            if (Schema::hasColumn('students', 'class_id')) {
                $table->dropForeign(['class_id']);
            }
        });
    }
}
