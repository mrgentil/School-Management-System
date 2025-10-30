<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('subject_id');   // subjects.id est UNSIGNED INTEGER
            $table->unsignedInteger('my_class_id');  // my_classes.id est UNSIGNED INTEGER
            $table->unsignedInteger('section_id');   // sections.id est UNSIGNED INTEGER
            $table->unsignedInteger('teacher_id');   // users.id est UNSIGNED INTEGER
            $table->datetime('due_date');
            $table->integer('max_score')->default(100);
            $table->string('file_path')->nullable();
            $table->enum('status', ['active', 'closed', 'draft'])->default('active');
            $table->timestamps();

            // Contraintes de clé étrangère avec les bons types
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade');
                  
            $table->foreign('my_class_id')
                  ->references('id')
                  ->on('my_classes')
                  ->onDelete('cascade');
                  
            $table->foreign('section_id')
                  ->references('id')
                  ->on('sections')
                  ->onDelete('cascade');
                  
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
