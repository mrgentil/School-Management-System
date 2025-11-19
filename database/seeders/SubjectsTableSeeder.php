<?php

namespace Database\Seeders;

use App\Models\MyClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
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
        DB::table('subjects')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->createSubjects();
    }

    protected function createSubjects()
    {
        // Matières selon le niveau scolaire RDC
        $teacher_id = User::where(['user_type' => 'teacher'])->first()->id;
        $my_classes = MyClass::all();

        foreach ($my_classes as $my_class) {
            $subjects_data = $this->getSubjectsForClass($my_class->name, $my_class->id, $teacher_id);
            
            if (!empty($subjects_data)) {
                DB::table('subjects')->insert($subjects_data);
            }
        }
    }

    protected function getSubjectsForClass($class_name, $class_id, $teacher_id)
    {
        $data = [];

        // Matières pour Crèche et Pré-Maternelle
        if (str_contains($class_name, 'Crèche') || str_contains($class_name, 'Pré-Maternelle')) {
            $subjects = [
                ['name' => 'Éveil', 'slug' => 'eveil'],
                ['name' => 'Jeux Éducatifs', 'slug' => 'jeux'],
                ['name' => 'Motricité', 'slug' => 'motricite'],
            ];
        }
        // Matières pour Maternelle
        elseif (str_contains($class_name, 'Maternelle')) {
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques', 'slug' => 'maths'],
                ['name' => 'Éveil Scientifique', 'slug' => 'eveil-sci'],
                ['name' => 'Éducation Artistique', 'slug' => 'art'],
                ['name' => 'Éducation Physique', 'slug' => 'eps'],
            ];
        }
        // Matières pour Primaire
        elseif (str_contains($class_name, 'Primaire')) {
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques', 'slug' => 'maths'],
                ['name' => 'Sciences', 'slug' => 'sciences'],
                ['name' => 'Histoire', 'slug' => 'histoire'],
                ['name' => 'Géographie', 'slug' => 'geo'],
                ['name' => 'Éducation Civique et Morale', 'slug' => 'ecm'],
                ['name' => 'Anglais', 'slug' => 'anglais'],
                ['name' => 'Éducation Artistique', 'slug' => 'art'],
                ['name' => 'Éducation Physique', 'slug' => 'eps'],
                ['name' => 'Religion', 'slug' => 'religion'],
            ];
        }
        // Matières pour 7ème et 8ème Année (1er cycle secondaire)
        elseif (str_contains($class_name, '7ème') || str_contains($class_name, '8ème')) {
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques', 'slug' => 'maths'],
                ['name' => 'Sciences Naturelles', 'slug' => 'sciences-nat'],
                ['name' => 'Physique', 'slug' => 'physique'],
                ['name' => 'Chimie', 'slug' => 'chimie'],
                ['name' => 'Histoire', 'slug' => 'histoire'],
                ['name' => 'Géographie', 'slug' => 'geo'],
                ['name' => 'Éducation Civique', 'slug' => 'ed-civique'],
                ['name' => 'Anglais', 'slug' => 'anglais'],
                ['name' => 'Éducation Artistique', 'slug' => 'art'],
                ['name' => 'Éducation Physique', 'slug' => 'eps'],
                ['name' => 'Religion', 'slug' => 'religion'],
            ];
        }
        // Matières pour Secondaire Générale (2ème cycle)
        elseif (str_contains($class_name, 'Secondaire Générale')) {
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques', 'slug' => 'maths'],
                ['name' => 'Physique', 'slug' => 'physique'],
                ['name' => 'Chimie', 'slug' => 'chimie'],
                ['name' => 'Biologie', 'slug' => 'biologie'],
                ['name' => 'Histoire', 'slug' => 'histoire'],
                ['name' => 'Géographie', 'slug' => 'geo'],
                ['name' => 'Philosophie', 'slug' => 'philo'],
                ['name' => 'Anglais', 'slug' => 'anglais'],
                ['name' => 'Éducation Civique', 'slug' => 'ed-civique'],
                ['name' => 'Éducation Physique', 'slug' => 'eps'],
                ['name' => 'Religion', 'slug' => 'religion'],
            ];
        }
        // Matières pour Secondaire Technique
        elseif (str_contains($class_name, 'Secondaire Technique')) {
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques', 'slug' => 'maths'],
                ['name' => 'Physique', 'slug' => 'physique'],
                ['name' => 'Chimie', 'slug' => 'chimie'],
                ['name' => 'Technologie', 'slug' => 'techno'],
                ['name' => 'Dessin Technique', 'slug' => 'dessin-tech'],
                ['name' => 'Électricité', 'slug' => 'electricite'],
                ['name' => 'Mécanique', 'slug' => 'mecanique'],
                ['name' => 'Anglais', 'slug' => 'anglais'],
                ['name' => 'Éducation Civique', 'slug' => 'ed-civique'],
                ['name' => 'Éducation Physique', 'slug' => 'eps'],
            ];
        }
        // Matières pour Secondaire Commerciale
        elseif (str_contains($class_name, 'Secondaire Commerciale')) {
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques Commerciales', 'slug' => 'maths-com'],
                ['name' => 'Comptabilité', 'slug' => 'comptabilite'],
                ['name' => 'Économie', 'slug' => 'economie'],
                ['name' => 'Gestion', 'slug' => 'gestion'],
                ['name' => 'Droit', 'slug' => 'droit'],
                ['name' => 'Secrétariat', 'slug' => 'secretariat'],
                ['name' => 'Informatique', 'slug' => 'informatique'],
                ['name' => 'Anglais', 'slug' => 'anglais'],
                ['name' => 'Éducation Civique', 'slug' => 'ed-civique'],
                ['name' => 'Éducation Physique', 'slug' => 'eps'],
            ];
        }
        else {
            // Matières par défaut
            $subjects = [
                ['name' => 'Français', 'slug' => 'francais'],
                ['name' => 'Mathématiques', 'slug' => 'maths'],
            ];
        }

        // Créer les données pour insertion
        foreach ($subjects as $subject) {
            $data[] = [
                'name' => $subject['name'],
                'slug' => $subject['slug'],
                'my_class_id' => $class_id,
                'teacher_id' => $teacher_id
            ];
        }

        return $data;
    }

}
