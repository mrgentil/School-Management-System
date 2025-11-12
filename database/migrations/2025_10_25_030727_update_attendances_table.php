<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'status')) {
                    $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
                }
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
        if (Schema::hasTable('attendances') && Schema::hasColumn('attendances', 'status')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
