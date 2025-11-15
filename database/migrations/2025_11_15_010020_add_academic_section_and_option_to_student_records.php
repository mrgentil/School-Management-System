<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicSectionAndOptionToStudentRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            if (!Schema::hasColumn('student_records', 'academic_section_id')) {
                $table->unsignedBigInteger('academic_section_id')->nullable()->after('section_id');
            }
            if (!Schema::hasColumn('student_records', 'option_id')) {
                $table->unsignedBigInteger('option_id')->nullable()->after('academic_section_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_records', function (Blueprint $table) {
            if (Schema::hasColumn('student_records', 'option_id')) {
                $table->dropColumn('option_id');
            }
            if (Schema::hasColumn('student_records', 'academic_section_id')) {
                $table->dropColumn('academic_section_id');
            }
        });
    }
}
