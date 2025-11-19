<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassTypesTableSeeder extends Seeder
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
        DB::table('class_types')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['name' => 'Crèche', 'code' => 'C'],
            ['name' => 'Pré-Maternelle', 'code' => 'PM'],
            ['name' => 'Maternelle', 'code' => 'M'],
            ['name' => 'Primaire', 'code' => 'P'],
            ['name' => 'Secondaire 1er Cycle', 'code' => 'S1'],
            ['name' => 'Secondaire 2ème Cycle', 'code' => 'S2'],
        ];

        DB::table('class_types')->insert($data);
    }
}
