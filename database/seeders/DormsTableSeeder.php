<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DormsTableSeeder extends Seeder
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
        DB::table('dorms')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'name' => 'Internat Foi',
                'description' => 'Internat pour garçons - Bâtiment principal (50 places)'
            ],
            [
                'name' => 'Internat Paix',
                'description' => 'Internat pour filles - Bâtiment nord (45 places)'
            ],
            [
                'name' => 'Internat Grâce',
                'description' => 'Internat mixte - Bâtiment sud (60 places)'
            ],
            [
                'name' => 'Internat Succès',
                'description' => 'Internat pour élèves du secondaire (40 places)'
            ],
            [
                'name' => 'Internat Confiance',
                'description' => 'Internat pour élèves du primaire (35 places)'
            ],
            [
                'name' => 'Internat Excellence',
                'description' => 'Internat pour élèves méritants (30 places)'
            ],
        ];
        DB::table('dorms')->insert($data);
    }
}
