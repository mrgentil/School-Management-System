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
        // Vérifier si la table existe déjà
        if (!Schema::hasTable('bulletin_publications')) {
            Schema::create('bulletin_publications', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('my_class_id')->nullable(); // null = toutes les classes
                $table->unsignedInteger('section_id')->nullable();
                $table->enum('type', ['period', 'semester'])->default('period');
                $table->tinyInteger('period')->nullable(); // 1-4 pour les périodes
                $table->tinyInteger('semester')->nullable(); // 1-2 pour les semestres
                $table->string('year', 20);
                $table->enum('status', ['draft', 'review', 'published'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('published_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                // Index pour recherche rapide
                $table->index(['my_class_id', 'type', 'period', 'year']);
                $table->index(['my_class_id', 'type', 'semester', 'year']);
                $table->index(['status', 'year']);

                // Foreign keys
                $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Table pour les notifications in-app
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type', 50); // 'bulletin_published', 'grade_updated', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Données additionnelles (class_id, period, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('bulletin_publications');
    }
};
