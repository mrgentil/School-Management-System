<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryRelatedTables extends Migration
{
    public function up()
    {
        // Table des catégories de livres
        Schema::create('book_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Table des livres - already exists, skip
        // Schema::create('books', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title');
        //     $table->string('author');
        //     $table->string('isbn')->unique();
        //     $table->text('description')->nullable();
        //     $table->integer('quantity')->default(1);
        //     $table->unsignedBigInteger('category_id')->nullable();
        //     $table->string('publisher')->nullable();
        //     $table->year('publication_year')->nullable();
        //     $table->string('edition')->nullable();
        //     $table->string('cover_image')->nullable();
        //     $table->enum('type', ['physique', 'numerique'])->default('physique');
        //     $table->string('file_path')->nullable(); // Pour les livres numériques
        //     $table->boolean('is_available')->default(true);
        //     $table->timestamps();
        // });

        // Table des emprunts
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('borrow_date');
            $table->dateTime('due_date');
            $table->dateTime('returned_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Table des réservations
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('reservation_date');
            $table->dateTime('expiration_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Table des avis
        Schema::create('book_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_reviews');
        Schema::dropIfExists('book_reservations');
        Schema::dropIfExists('book_loans');
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_categories');
    }
}
