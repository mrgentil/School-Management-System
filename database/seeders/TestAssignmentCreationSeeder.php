<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;

class TestAssignmentCreationSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST DES DONNÉES POUR CRÉATION DE DEVOIR...\n\n";
        
        // Simuler les données du contrôleur
        echo "📋 CHARGEMENT DES DONNÉES...\n";
        
        // Classes avec relations
        $classes = MyClass::with(['academicSection', 'option', 'section'])
            ->orderBy('name')
            ->take(5)
            ->get();
            
        echo "✅ Classes chargées: " . $classes->count() . "\n";
        foreach ($classes as $class) {
            echo "   ├─ {$class->name}\n";
            echo "   │  ├─ Nom complet: " . ($class->full_name ?: 'N/A') . "\n";
            echo "   │  ├─ Section académique: " . ($class->academicSection ? $class->academicSection->name : 'N/A') . "\n";
            echo "   │  ├─ Option: " . ($class->option ? $class->option->name : 'N/A') . "\n";
            echo "   │  └─ Sections: " . $class->section->count() . "\n";
        }
        
        // Sections groupées par classe
        $sections = Section::with('my_class')
            ->orderBy('name')
            ->get()
            ->groupBy('my_class_id');
            
        echo "\n✅ Sections groupées par classe:\n";
        foreach ($sections as $classId => $classSections) {
            $className = $classSections->first()->my_class->name ?? 'Inconnue';
            echo "   ├─ Classe {$className} (ID: {$classId}): " . $classSections->count() . " sections\n";
            foreach ($classSections as $section) {
                echo "   │  └─ {$section->name}\n";
            }
        }
        
        // Matières
        $subjects = Subject::orderBy('name')->take(10)->get();
        echo "\n✅ Matières disponibles: " . $subjects->count() . "\n";
        foreach ($subjects as $subject) {
            echo "   ├─ {$subject->name}\n";
        }
        
        // Périodes
        $periods = [
            ['id' => 1, 'name' => 'Période 1', 'semester' => 1, 'description' => 'Première période du semestre 1'],
            ['id' => 2, 'name' => 'Période 2', 'semester' => 1, 'description' => 'Deuxième période du semestre 1'],
            ['id' => 3, 'name' => 'Période 3', 'semester' => 2, 'description' => 'Première période du semestre 2'],
            ['id' => 4, 'name' => 'Période 4', 'semester' => 2, 'description' => 'Deuxième période du semestre 2'],
        ];
        
        echo "\n✅ Périodes RDC: " . count($periods) . "\n";
        foreach ($periods as $period) {
            echo "   ├─ {$period['name']} (Semestre {$period['semester']})\n";
        }
        
        echo "\n🎉 TOUTES LES DONNÉES SONT PRÊTES POUR LA CRÉATION DE DEVOIR!\n";
        echo "💡 Les données utilisent maintenant les vraies relations de la base de données.\n";
    }
}
