<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectGradesConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_grades_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('subject_id');
            $table->decimal('period_max_points', 5, 2)->default(20.00); // Points max pour période (ex: 20)
            $table->decimal('exam_max_points', 5, 2)->default(40.00);   // Points max pour examen (ex: 40)
            $table->string('academic_year'); // Année académique (ex: 2025-2026)
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable(); // Notes administratives
            $table->timestamps();

            // Index et contraintes
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            
            // Contrainte unique : une seule config par classe/matière/année
            $table->unique(['my_class_id', 'subject_id', 'academic_year'], 'unique_class_subject_year');
            
            // Index pour performance
            $table->index(['my_class_id', 'academic_year']);
            $table->index(['subject_id', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_grades_config');
    }
}
