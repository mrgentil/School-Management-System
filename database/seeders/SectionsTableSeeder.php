<?php
namespace Database\Seeders;

use App\Models\MyClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SectionsTableSeeder extends Seeder
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
        DB::table('sections')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $c = MyClass::pluck('id')->all();

        // Créer des divisions A, B, C, D pour chaque classe
        $data = [];
        $divisions = ['A', 'B', 'C', 'D'];
        
        // Pour chaque classe, créer les divisions A, B, C, D
        foreach ($c as $index => $class_id) {
            foreach ($divisions as $division) {
                $data[] = [
                    'name' => $division,
                    'my_class_id' => $class_id,
                    'active' => 1
                ];
            }
        }

        DB::table('sections')->insert($data);
    }
}
