<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBookRequestsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('book_requests');
        
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('student_id');  // Utilise user_id de la table students
            $table->date('request_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned'])->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamps();

            // FK for column 'book_id' moved to final migration (auto-generated).
// Original: $table->foreign('book_id')
//                   ->references('id')
//                   ->on('books')
//                   ->onDelete('cascade');
         
            // FK for column 'student_id' moved to final migration (auto-generated).
// Original: $table->foreign('student_id')
//                   ->references('user_id')
//                   ->on('students')
//                   ->onDelete('cascade');
         
            // FK for column 'approved_by' moved to final migration (auto-generated).
// Original: $table->foreign('approved_by')
//                   ->references('id')
//                   ->on('users')
//                   ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_requests');
    }
}
