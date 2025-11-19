<?php
namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyClassesTableSeeder extends Seeder
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
        DB::table('my_classes')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $ct = ClassType::pluck('id')->all();

        $data = [
            // Crèche (0 = Crèche)
            ['name' => 'Crèche Petite Section', 'class_type_id' => $ct[0]],
            ['name' => 'Crèche Grande Section', 'class_type_id' => $ct[0]],
            
            // Pré-Maternelle (1 = Pré-Maternelle)
            ['name' => 'Pré-Maternelle', 'class_type_id' => $ct[1]],
            
            // Maternelle (2 = Maternelle)
            ['name' => 'Maternelle 1ère Année', 'class_type_id' => $ct[2]],
            ['name' => 'Maternelle 2ème Année', 'class_type_id' => $ct[2]],
            ['name' => 'Maternelle 3ème Année', 'class_type_id' => $ct[2]],
            
            // Primaire (3 = Primaire)
            ['name' => '1ère Année Primaire', 'class_type_id' => $ct[3]],
            ['name' => '2ème Année Primaire', 'class_type_id' => $ct[3]],
            ['name' => '3ème Année Primaire', 'class_type_id' => $ct[3]],
            ['name' => '4ème Année Primaire', 'class_type_id' => $ct[3]],
            ['name' => '5ème Année Primaire', 'class_type_id' => $ct[3]],
            ['name' => '6ème Année Primaire', 'class_type_id' => $ct[3]],
            
            // Secondaire 1er Cycle (4 = Secondaire 1er Cycle)
            ['name' => '7ème Année (1ère Secondaire)', 'class_type_id' => $ct[4]],
            ['name' => '8ème Année (2ème Secondaire)', 'class_type_id' => $ct[4]],
            
            // Secondaire 2ème Cycle (5 = Secondaire 2ème Cycle)
            ['name' => '3ème Secondaire Générale', 'class_type_id' => $ct[5]],
            ['name' => '4ème Secondaire Générale', 'class_type_id' => $ct[5]],
            ['name' => '5ème Secondaire Générale', 'class_type_id' => $ct[5]],
            ['name' => '6ème Secondaire Générale', 'class_type_id' => $ct[5]],
            
            // Sections techniques (Secondaire 2ème Cycle)
            ['name' => '3ème Secondaire Technique', 'class_type_id' => $ct[5]],
            ['name' => '4ème Secondaire Technique', 'class_type_id' => $ct[5]],
            ['name' => '5ème Secondaire Technique', 'class_type_id' => $ct[5]],
            ['name' => '6ème Secondaire Technique', 'class_type_id' => $ct[5]],
            
            // Sections commerciales (Secondaire 2ème Cycle)
            ['name' => '3ème Secondaire Commerciale', 'class_type_id' => $ct[5]],
            ['name' => '4ème Secondaire Commerciale', 'class_type_id' => $ct[5]],
            ['name' => '5ème Secondaire Commerciale', 'class_type_id' => $ct[5]],
            ['name' => '6ème Secondaire Commerciale', 'class_type_id' => $ct[5]],
        ];

        DB::table('my_classes')->insert($data);
    }
}
