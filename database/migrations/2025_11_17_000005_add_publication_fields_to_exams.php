<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicationFieldsToExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'grading', 'published', 'archived'])->default('draft')->after('year');
            $table->boolean('results_published')->default(false)->after('status');
            $table->timestamp('published_at')->nullable()->after('results_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['status', 'results_published', 'published_at']);
        });
    }
}
