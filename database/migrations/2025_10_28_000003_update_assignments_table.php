<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'class_id')) {
                $table->unsignedInteger('class_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('assignments', 'section_id')) {
                $table->unsignedInteger('section_id')->nullable()->after('class_id');
            }
        });
    }

    public function down()
    {
        if (Schema::hasColumn('assignments', 'class_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropColumn(['class_id', 'section_id']);
            });
        }
    }
}
