<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsTableSeeder extends Seeder
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
        DB::table('skills')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->createSkills();
    }

    protected function createSkills()
    {
        $types = ['AF', 'PS']; // Compétences Affectives & Psychomotrices
        $d = [
            // Compétences Affectives (AF)
            [ 'name' => 'PONCTUALITÉ', 'skill_type' => $types[0] ],
            [ 'name' => 'PROPRETÉ', 'skill_type' => $types[0] ],
            [ 'name' => 'HONNÊTETÉ', 'skill_type' => $types[0] ],
            [ 'name' => 'FIABILITÉ', 'skill_type' => $types[0] ],
            [ 'name' => 'RELATIONS AVEC LES AUTRES', 'skill_type' => $types[0] ],
            [ 'name' => 'POLITESSE', 'skill_type' => $types[0] ],
            [ 'name' => 'ATTENTION', 'skill_type' => $types[0] ],
            [ 'name' => 'RESPECT DES RÈGLES', 'skill_type' => $types[0] ],
            [ 'name' => 'ESPRIT D\'ÉQUIPE', 'skill_type' => $types[0] ],
            [ 'name' => 'LEADERSHIP', 'skill_type' => $types[0] ],
            
            // Compétences Psychomotrices (PS)
            [ 'name' => 'ÉCRITURE MANUSCRITE', 'skill_type' => $types[1] ],
            [ 'name' => 'JEUX ET SPORTS', 'skill_type' => $types[1] ],
            [ 'name' => 'DESSIN ET ARTS', 'skill_type' => $types[1] ],
            [ 'name' => 'PEINTURE', 'skill_type' => $types[1] ],
            [ 'name' => 'CONSTRUCTION', 'skill_type' => $types[1] ],
            [ 'name' => 'COMPÉTENCES MUSICALES', 'skill_type' => $types[1] ],
            [ 'name' => 'FLEXIBILITÉ', 'skill_type' => $types[1] ],
            [ 'name' => 'COORDINATION MOTRICE', 'skill_type' => $types[1] ],
            [ 'name' => 'TRAVAUX MANUELS', 'skill_type' => $types[1] ],
            [ 'name' => 'EXPRESSION CORPORELLE', 'skill_type' => $types[1] ],
        ];
        
        DB::table('skills')->insert($d);
    }

}
