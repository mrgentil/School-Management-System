<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBookRequestsTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_requests', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            $table->dropColumn([
                'titre', 
                'auteur', 
                'isbn', 
                'description', 
                'statut', 
                'reponse', 
                'etudiant_id', 
                'bibliothecaire_id', 
                'date_demande', 
                'date_traitement'
            ]);
            
            // Ajouter les nouvelles colonnes
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('request_date')->useCurrent();
            $table->timestamp('expected_return_date');
            $table->timestamp('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned'])->default('pending');
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_requests', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropForeign(['book_id']);
            $table->dropForeign(['student_id']);
            $table->dropForeign(['approved_by']);
            
            $table->dropColumn([
                'book_id',
                'student_id',
                'approved_by',
                'request_date',
                'expected_return_date',
                'actual_return_date',
                'status',
                'remarks'
            ]);
            
            // Recréer les anciennes colonnes
            $table->string('titre');
            $table->string('auteur');
            $table->string('isbn')->nullable();
            $table->text('description')->nullable();
            $table->enum('statut', ['en_attente', 'approuve', 'refuse'])->default('en_attente');
            $table->text('reponse')->nullable();
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bibliothecaire_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_traitement')->nullable();
        });
    }
}
