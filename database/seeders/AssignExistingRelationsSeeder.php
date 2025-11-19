<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Option;
use App\Models\AcademicSection;

class AssignExistingRelationsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔗 ASSIGNATION DES RELATIONS EXISTANTES...\n\n";
        
        // Récupérer les données disponibles
        $options = Option::all();
        $academicSections = AcademicSection::all();
        
        echo "📋 DONNÉES DISPONIBLES:\n";
        echo "Options: " . $options->pluck('name')->implode(', ') . "\n";
        echo "Academic Sections: " . $academicSections->pluck('name')->implode(', ') . "\n\n";
        
        $updated = 0;
        
        // Assigner selon le nom de la classe
        $classes = MyClass::all();
        
        foreach ($classes as $class) {
            $updated_class = false;
            
            // Assigner Academic Section selon le type
            if (str_contains($class->name, 'Technique') || str_contains($class->name, 'Informatique')) {
                $techSection = $academicSections->where('name', 'Technique')->first();
                if ($techSection) {
                    $class->academic_section_id = $techSection->id;
                    $updated_class = true;
                }
            } elseif (str_contains($class->name, 'Commercial')) {
                $commercialSection = $academicSections->where('name', 'Commercial')->first();
                if ($commercialSection) {
                    $class->academic_section_id = $commercialSection->id;
                    $updated_class = true;
                }
            } elseif (str_contains($class->name, 'Secondaire') || str_contains($class->name, 'Sec')) {
                $secondaireSection = $academicSections->where('name', 'Sécondaire')->first();
                if ($secondaireSection) {
                    $class->academic_section_id = $secondaireSection->id;
                    $updated_class = true;
                }
            }
            
            // Assigner Option selon le nom
            if (str_contains($class->name, 'Informatique')) {
                $infoOption = $options->where('name', 'Commercial Informatique')->first();
                if ($infoOption) {
                    $class->option_id = $infoOption->id;
                    $updated_class = true;
                }
            } elseif (str_contains($class->name, 'Electronique')) {
                $elecOption = $options->where('name', 'Electronique')->first();
                if ($elecOption) {
                    $class->option_id = $elecOption->id;
                    $updated_class = true;
                }
            } elseif (str_contains($class->name, 'Mécanique')) {
                $mecOption = $options->where('name', 'Mécanique')->first();
                if ($mecOption) {
                    $class->option_id = $mecOption->id;
                    $updated_class = true;
                }
            } elseif (str_contains($class->name, 'Biochimie')) {
                $bioOption = $options->where('name', 'Biochimie')->first();
                if ($bioOption) {
                    $class->option_id = $bioOption->id;
                    $updated_class = true;
                }
            } elseif (str_contains($class->name, 'Secondaire') || str_contains($class->name, 'Sec')) {
                $secOption = $options->where('name', 'Secondaire')->first();
                if ($secOption) {
                    $class->option_id = $secOption->id;
                    $updated_class = true;
                }
            }
            
            if ($updated_class) {
                $class->save();
                echo "✅ {$class->name} - Section: " . ($class->academicSection->name ?? 'N/A') . ", Option: " . ($class->option->name ?? 'N/A') . "\n";
                $updated++;
            } else {
                echo "⚪ {$class->name} - Aucune relation assignée\n";
            }
        }
        
        echo "\n🎉 TERMINÉ! {$updated} classes mises à jour.\n";
    }
}
