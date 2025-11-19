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
        Schema::table('my_classes', function (Blueprint $table) {
            // Ajouter les colonnes pour les relations avec les tables existantes
            $table->unsignedBigInteger('academic_section_id')->nullable()->after('class_type_id');
            $table->unsignedBigInteger('option_id')->nullable()->after('academic_section_id');
            
            // Ajouter les clés étrangères
            $table->foreign('academic_section_id')->references('id')->on('academic_sections')->onDelete('set null');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_classes', function (Blueprint $table) {
            // Supprimer les clés étrangères
            $table->dropForeign(['academic_section_id']);
            $table->dropForeign(['option_id']);
            
            // Supprimer les colonnes
            $table->dropColumn(['academic_section_id', 'option_id']);
        });
    }
};
