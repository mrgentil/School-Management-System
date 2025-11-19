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
        Schema::create('academic_options', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Biochimie, Électronique, Comptabilité, etc.
            $table->string('code')->nullable(); // BCH, ELEC, COMPT, etc.
            $table->text('description')->nullable();
            $table->unsignedInteger('class_type_id');
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('cascade');
            $table->index(['class_type_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_options');
    }
};
