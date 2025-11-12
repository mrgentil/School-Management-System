<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    // D'abord, supprimer l'ancienne contrainte si elle existe
    if (Schema::hasTable('student_records')) {
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME AS name
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'student_records'
              AND COLUMN_NAME = 'user_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($constraints)) {
            Schema::table('student_records', function ($table) use ($constraints) {
                foreach ($constraints as $c) {
                    $table->dropForeign($c->name);
                }
            });
        }
    }

    // Ensuite, modifier la colonne pour la rendre nullable
    Schema::table('student_records', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable()->change();
    });

    // Enfin, recréer la contrainte avec les bonnes options
    Schema::table('student_records', function (Blueprint $table) {
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('set null')
              ->onUpdate('cascade');
    });
}

public function down()
{
    // Supprimer la contrainte
    $constraints = DB::select("
        SELECT CONSTRAINT_NAME AS name
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'student_records'
          AND COLUMN_NAME = 'user_id'
          AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    if (!empty($constraints)) {
        Schema::table('student_records', function ($table) use ($constraints) {
            foreach ($constraints as $c) {
                $table->dropForeign($c->name);
            }
        });
    }

    // Remettre la colonne en NOT NULL
    Schema::table('student_records', function (Blueprint $table) {
        // D'abord, s'assurer qu'il n'y a pas de valeurs NULL
        DB::statement('UPDATE student_records SET user_id = 0 WHERE user_id IS NULL');
        
        // Puis modifier la colonne
        $table->unsignedBigInteger('user_id')->nullable(false)->change();
    });

    // Recréer la contrainte sans ON DELETE SET NULL
    Schema::table('student_records', function (Blueprint $table) {
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('restrict')
              ->onUpdate('cascade');
    });
}
};
