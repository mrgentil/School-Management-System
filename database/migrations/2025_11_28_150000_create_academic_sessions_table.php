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
        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ex: 2024-2025
            $table->string('label')->nullable(); // Ex: Année Scolaire 2024-2025
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->enum('status', ['active', 'archived', 'upcoming'])->default('active');
            $table->text('description')->nullable();
            
            // Statistiques (calculées automatiquement)
            $table->integer('total_students')->default(0);
            $table->integer('total_teachers')->default(0);
            $table->integer('total_classes')->default(0);
            $table->decimal('average_score', 5, 2)->nullable();
            
            // Dates importantes
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->date('exam_start')->nullable();
            $table->date('exam_end')->nullable();
            
            $table->timestamps();
        });

        // Créer la session actuelle si elle n'existe pas
        $currentSession = \App\Helpers\Qs::getCurrentSession();
        if ($currentSession) {
            \DB::table('academic_sessions')->insert([
                'name' => $currentSession,
                'label' => 'Année Scolaire ' . $currentSession,
                'is_current' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_sessions');
    }
};
