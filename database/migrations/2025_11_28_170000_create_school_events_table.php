<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('type', [
                'holiday',      // Congé/Vacances
                'exam',         // Examen
                'meeting',      // Réunion
                'event',        // Événement général
                'deadline',     // Date limite
                'activity',     // Activité scolaire
            ])->default('event');
            $table->string('color')->default('#2196F3');
            $table->boolean('is_all_day')->default(true);
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // weekly, monthly, yearly
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('target_audience')->default('all'); // all, students, teachers, parents
            $table->boolean('send_notification')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_events');
    }
};
