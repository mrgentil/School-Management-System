<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size'); // en bytes
            $table->string('file_type', 50);
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('my_class_id')->nullable();
            $table->unsignedInteger('uploaded_by');
            $table->boolean('is_public')->default(true);
            $table->integer('download_count')->default(0);
            $table->timestamps();

            // FK for column 'subject_id' moved to final migration (auto-generated).
// Original: $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            // FK for column 'my_class_id' moved to final migration (auto-generated).
// Original: $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('set null');
            // FK for column 'uploaded_by' moved to final migration (auto-generated).
// Original: $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['my_class_id', 'subject_id']);
            $table->index('is_public');
        });
    }

    public function down()
    {
        Schema::dropIfExists('study_materials');
    }
}
