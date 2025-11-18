<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSupervisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_supervisors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_schedule_id');
            $table->unsignedBigInteger('teacher_id');
            $table->enum('role', ['primary', 'assistant'])->default('primary');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_supervisors');
    }
}
