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
        // Vérifier si la table existe déjà
        if (Schema::hasTable('exam_student_placements')) {
            return;
        }

        Schema::create('exam_student_placements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('exam_id'); // Utiliser unsignedInteger pour correspondre à la table exams
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('exam_room_id');
            $table->integer('seat_number');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_room_id')->references('id')->on('exam_rooms')->onDelete('cascade');

            // Index unique pour éviter les doublons
            $table->unique(['exam_id', 'student_id']);
            $table->unique(['exam_room_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_student_placements');
    }
};
