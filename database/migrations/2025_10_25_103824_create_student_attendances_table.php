<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('marked_by');
            $table->timestamps();

            // Clés étrangères
            // // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')
            //       ->references('id')
            //       ->on('users')
            //       ->onDelete('cascade');

            // // FK for column 'class_id' moved to final migration (auto-generated).
// Original: $table->foreign('class_id')
            //       ->references('id')
            //       ->on('my_classes')
            //       ->onDelete('cascade');

            // // FK for column 'section_id' moved to final migration (auto-generated).
// Original: $table->foreign('section_id')
            //       ->references('id')
            //       ->on('sections')
            //       ->onDelete('cascade');

            // // FK for column 'subject_id' moved to final migration (auto-generated).
// Original: $table->foreign('subject_id')
            //       ->references('id')
            //       ->on('subjects')
            //       ->onDelete('set null');

            // // FK for column 'marked_by' moved to final migration (auto-generated).
// Original: $table->foreign('marked_by')
            //       ->references('id')
            //       ->on('users')
            //       ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_attendances');
    }
}
