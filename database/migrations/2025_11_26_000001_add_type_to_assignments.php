<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToAssignments extends Migration
{
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'type')) {
                $table->enum('type', ['devoir', 'interrogation'])->default('devoir')->after('title')
                    ->comment('Type: devoir ou interrogation');
            }
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
