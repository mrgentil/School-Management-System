<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('auteur');
            $table->string('isbn')->nullable();
            $table->text('description')->nullable();
            $table->enum('statut', ['en_attente', 'approuve', 'refuse'])->default('en_attente');
            $table->text('reponse')->nullable();
            $table->unsignedBigInteger('etudiant_id');
            $table->unsignedBigInteger('bibliothecaire_id')->nullable();
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_requests');
    }
}
