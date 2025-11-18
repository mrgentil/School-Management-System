<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionToExamSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('exam_schedules', 'option_id')) {
            Schema::table('exam_schedules', function (Blueprint $table) {
                $table->unsignedBigInteger('option_id')->nullable()->after('section_id');
                $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('exam_schedules', 'option_id')) {
            Schema::table('exam_schedules', function (Blueprint $table) {
                $table->dropForeign(['option_id']);
                $table->dropColumn('option_id');
            });
        }
    }
}
