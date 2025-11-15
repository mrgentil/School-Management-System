<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConvertTermToSemesterData extends Migration
{
    /**
     * Run the migrations.
     * Convertir les données existantes de term vers semester
     *
     * @return void
     */
    public function up()
    {
        // Convertir les examens existants
        // Term 1, 2 -> Semester 1
        // Term 3 -> Semester 2
        
        DB::statement('UPDATE exams SET semester = 1 WHERE semester IN (1, 2)');
        DB::statement('UPDATE exams SET semester = 2 WHERE semester = 3');

        // Mettre à jour les devoirs : donner période 1 par défaut aux devoirs existants
        DB::statement('UPDATE assignments SET period = 1 WHERE period IS NULL OR period = 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Pas de rollback car c'est une conversion de données
    }
}
