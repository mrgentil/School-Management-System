<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolEventsTable extends Migration
{
    public function up()
    {
        Schema::create('school_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->enum('event_type', ['academic', 'sports', 'cultural', 'meeting', 'exam', 'holiday', 'other'])->default('other');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, yearly
            $table->enum('target_audience', ['all', 'students', 'teachers', 'parents', 'staff'])->default('all');
            $table->string('color', 7)->default('#3788d8'); // Couleur pour le calendrier
            $table->unsignedInteger('created_by');
            $table->timestamps();

            // FK for column 'created_by' moved to final migration (auto-generated).
// Original: $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['event_date', 'target_audience']);
            $table->index('event_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_events');
    }
}
