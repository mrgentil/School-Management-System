<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute le système d'examens Session / Hors Session
     */
    public function up()
    {
        // Ajouter le type d'examen
        if (!Schema::hasColumn('exams', 'exam_type')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->enum('exam_type', ['hors_session', 'session'])->default('hors_session')->after('semester');
            });
        }

        // Créer la table des salles d'examen (pour les Sessions uniquement)
        if (!Schema::hasTable('exam_rooms')) {
            Schema::create('exam_rooms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name'); // Salle A, Salle B, etc.
                $table->string('code')->unique(); // A, B, C, etc.
                $table->string('building')->nullable(); // Bâtiment
                $table->integer('capacity'); // Capacité maximale
                $table->enum('level', ['excellence', 'moyen', 'faible'])->default('moyen');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Modifier exam_schedules pour supporter les deux types
        if (!Schema::hasColumn('exam_schedules', 'exam_room_id')) {
            Schema::table('exam_schedules', function (Blueprint $table) {
                // Pour HORS SESSION : pas besoin de room, juste my_class_id
                // Pour SESSION : besoin de room et placement des étudiants
                $table->unsignedInteger('exam_room_id')->nullable()->after('section_id');
                $table->foreign('exam_room_id')->references('id')->on('exam_rooms')->onDelete('set null');
            });
        }

        // Table pour le placement des étudiants (SESSION uniquement)
        if (!Schema::hasTable('exam_student_placements')) {
            Schema::create('exam_student_placements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_schedule_id');
            $table->unsignedBigInteger('student_id'); // ← Changé en unsignedBigInteger pour correspondre à users.id
            $table->unsignedInteger('exam_room_id');
            $table->integer('seat_number')->nullable(); // Numéro de place
            $table->decimal('ranking_score', 8, 2)->nullable(); // Score utilisé pour le classement
            $table->enum('performance_level', ['excellence', 'moyen', 'faible'])->nullable();
            $table->timestamps();

            $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_room_id')->references('id')->on('exam_rooms')->onDelete('cascade');

            // Un étudiant ne peut être placé qu'une fois par horaire d'examen
            $table->unique(['exam_schedule_id', 'student_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('exam_student_placements');
        
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropForeign(['exam_room_id']);
            $table->dropColumn('exam_room_id');
        });
        
        Schema::dropIfExists('exam_rooms');
        
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('exam_type');
        });
    }
};
