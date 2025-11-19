<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider la table
        DB::table('grades')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->createGrades();
    }

    protected function createGrades()
    {

        $d = [
            ['name' => 'A', 'mark_from' => 80, 'mark_to' => 100, 'remark' => 'Excellent'],
            ['name' => 'B', 'mark_from' => 70, 'mark_to' => 79, 'remark' => 'Très Bien'],
            ['name' => 'C', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Bien'],
            ['name' => 'D', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Assez Bien'],
            ['name' => 'E', 'mark_from' => 40, 'mark_to' => 49, 'remark' => 'Échec'],
            ['name' => 'F', 'mark_from' => 0, 'mark_to' => 39, 'remark' => 'Naffer'],
        ];
        DB::table('grades')->insert($d);
    }
}
