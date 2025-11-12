<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pins', function (Blueprint $table) {
            // S'assurer que les colonnes sont nullable si onDelete('set null')
            if (Schema::hasColumn('pins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            if (Schema::hasColumn('pins', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->change();
            }
            // Ajout des clés étrangères si besoin
            if (!Schema::hasColumn('pins', 'user_id_foreign')) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
            if (!Schema::hasColumn('pins', 'student_id_foreign')) {
                $table->foreign('student_id')
                      ->references('id')
                      ->on('students')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pins', function (Blueprint $table) {
        // Drop foreign keys if they exist
        if (DB::getDriverName() !== 'sqlite') {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['student_id']);
        }
    });
    }
};
