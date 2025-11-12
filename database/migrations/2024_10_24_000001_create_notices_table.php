<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['announcement', 'event', 'urgent', 'general'])->default('general');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('target_audience', ['all', 'students', 'teachers', 'parents', 'staff'])->default('all');
            $table->unsignedInteger('created_by');
            $table->timestamps();

            // FK for column 'created_by' moved to final migration (auto-generated).
// Original: $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['is_active', 'start_date', 'end_date']);
            $table->index('target_audience');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
