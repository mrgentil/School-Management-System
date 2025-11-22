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
        Schema::table('marks', function (Blueprint $table) {
            $table->enum('evaluation_type', ['devoir', 'interrogation', 'interrogation_generale', 'examen'])
                  ->default('devoir')
                  ->after('year')
                  ->comment('Type d\'évaluation: devoir, interrogation, interrogation_generale, examen');
            
            $table->decimal('max_points', 5, 2)
                  ->nullable()
                  ->after('evaluation_type')
                  ->comment('Cote maximale spécifique pour cette évaluation (optionnel)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropColumn(['evaluation_type', 'max_points']);
        });
    }
};
