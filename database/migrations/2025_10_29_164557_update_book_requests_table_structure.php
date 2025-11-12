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
        // Skip this migration as the table structure is already correct
        // Schema::table('book_requests', function (Blueprint $table) {
        //     // Supprimer les anciennes colonnes
        //     $table->dropColumn([
        //         'titre',
        //         'auteur',
        //         'isbn',
        //         'description',
        //         'statut',
        //         'reponse',
        //         'etudiant_id',
        //         'bibliothecaire_id',
        //         'date_demande',
        //         'date_traitement'
        //     ]);

        //     // Ajouter les nouvelles colonnes
        //     $table->unsignedBigInteger('book_id');
        //     $table->unsignedBigInteger('student_id');
        //     $table->unsignedBigInteger('approved_by')->nullable();
        //     $table->timestamp('request_date')->useCurrent();
        //     $table->timestamp('expected_return_date');
        //     $table->timestamp('actual_return_date')->nullable();
        //     $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned'])->default('pending');
        //     $table->text('remarks')->nullable();
        // });
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
                                // Safely drop foreign key for column 'book_id' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_requests' AND COLUMN_NAME = 'book_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_requests', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

                                // Safely drop foreign key for column 'student_id' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_requests' AND COLUMN_NAME = 'student_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_requests', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }

                                // Safely drop foreign key for column 'approved_by' if it exists
                    $constraint = DB::selectOne("SELECT CONSTRAINT_NAME AS name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'book_requests' AND COLUMN_NAME = 'approved_by' AND REFERENCED_TABLE_NAME IS NOT NULL");
                    if ($constraint && isset($constraint->name)) {
                        Schema::table('book_requests', function (Blueprint $table) use ($constraint) {
                            $table->dropForeign($constraint->name);
                        });
                    }


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
            $table->unsignedBigInteger('etudiant_id');
            $table->unsignedBigInteger('bibliothecaire_id')->nullable();
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_traitement')->nullable();
        });
    }
}
