<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Colonnes attendues par le seeder
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->date('date');
            $table->time('time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'excused', 'late_justified', 'absent_justified']);
            $table->text('notes')->nullable();
            // Conservé pour compatibilité existante
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
