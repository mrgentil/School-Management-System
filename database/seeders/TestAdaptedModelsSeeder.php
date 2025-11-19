<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Option;
use App\Models\AcademicSection;

class TestAdaptedModelsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST DES MODÈLES ADAPTÉS...\n\n";
        
        // Prendre une classe d'exemple
        $class = MyClass::first();
        
        if ($class) {
            echo "📋 CLASSE TESTÉE: {$class->name} (ID: {$class->id})\n";
            echo "   ├─ academic_section_id: " . ($class->academic_section_id ?? 'null') . "\n";
            echo "   ├─ option_id: " . ($class->option_id ?? 'null') . "\n";
            
            // Tester les relations
            try {
                if ($class->academicSection) {
                    echo "   ├─ Academic Section: " . $class->academicSection->name . "\n";
                } else {
                    echo "   ├─ Academic Section: Non assignée\n";
                }
                
                if ($class->option) {
                    echo "   ├─ Option: " . $class->option->name . "\n";
                } else {
                    echo "   ├─ Option: Non assignée\n";
                }
                
                // Sections (divisions)
                $sections = $class->section;
                echo "   ├─ Sections (divisions): " . $sections->count() . "\n";
                foreach ($sections as $section) {
                    echo "   │  └─ " . $section->name . "\n";
                }
                
                // Nom complet
                echo "   └─ Nom complet: " . ($class->full_name ?: 'N/A') . "\n";
                
            } catch (\Exception $e) {
                echo "   ❌ Erreur: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n📊 DONNÉES DISPONIBLES:\n";
        echo "   ├─ Options: " . Option::count() . "\n";
        echo "   ├─ Academic Sections: " . AcademicSection::count() . "\n";
        echo "   └─ Classes: " . MyClass::count() . "\n";
        
        echo "\n✅ TEST TERMINÉ!\n";
    }
}
