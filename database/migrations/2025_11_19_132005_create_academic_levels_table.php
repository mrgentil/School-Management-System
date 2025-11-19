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
        Schema::create('academic_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 1ère, 2ème, 3ème, etc.
            $table->string('display_name'); // 1ère Année Primaire, 2ème Année Primaire, etc.
            $table->unsignedInteger('class_type_id');
            $table->integer('order')->default(0); // Pour l'ordre d'affichage
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
        Schema::dropIfExists('academic_levels');
    }
};
