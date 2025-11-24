<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Qs;

class AddYearToAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'year')) {
                $table->string('year', 10)->nullable()->after('period')
                    ->comment('Année scolaire (ex: 2025-2026)');
            }
        });

        // Mettre à jour les devoirs existants avec l'année scolaire actuelle
        $currentYear = Qs::getSetting('current_session');
        if ($currentYear) {
            DB::table('assignments')
                ->whereNull('year')
                ->update(['year' => $currentYear]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
}
