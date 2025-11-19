<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\AcademicLevel;
use App\Models\AcademicOption;

class ClassInfoDiagnosticSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DIAGNOSTIC COMPLET DES INFORMATIONS DE CLASSE...\n\n";
        
        // Prendre quelques classes d'exemple
        $classes = MyClass::take(5)->get();
        
        foreach ($classes as $class) {
            echo "📋 CLASSE: {$class->name} (ID: {$class->id})\n";
            echo "   ├─ Type de classe: " . ($class->class_type ? $class->class_type->name : 'N/A') . "\n";
            
            // Informations stockées directement
            echo "   ├─ Division stockée: " . ($class->division ?? 'null') . "\n";
            echo "   ├─ Option stockée: " . ($class->academic_option ?? 'null') . "\n";
            echo "   ├─ Level stocké: " . ($class->academic_level ?? 'null') . "\n";
            
            // IDs des relations
            echo "   ├─ Academic Level ID: " . ($class->academic_level_id ?? 'null') . "\n";
            echo "   ├─ Academic Option ID: " . ($class->academic_option_id ?? 'null') . "\n";
            
            // Relations
            try {
                if ($class->academicLevel) {
                    echo "   ├─ Academic Level (relation): " . $class->academicLevel->name . "\n";
                }
                if ($class->academicOption) {
                    echo "   ├─ Academic Option (relation): " . $class->academicOption->name . "\n";
                }
            } catch (\Exception $e) {
                echo "   ├─ Erreur relations: " . $e->getMessage() . "\n";
            }
            
            // Sections liées
            $sections = Section::where('my_class_id', $class->id)->get();
            echo "   ├─ Sections liées: " . $sections->count() . "\n";
            foreach ($sections as $section) {
                echo "   │  └─ " . $section->name . " (ID: {$section->id})\n";
            }
            
            // Nom complet généré
            echo "   └─ Nom complet: " . ($class->full_name ?: 'N/A') . "\n";
            echo "\n";
        }
        
        echo "📊 RÉSUMÉ DES TABLES:\n";
        echo "   ├─ Classes: " . MyClass::count() . "\n";
        echo "   ├─ Sections: " . Section::count() . "\n";
        echo "   ├─ Academic Levels: " . AcademicLevel::count() . "\n";
        echo "   └─ Academic Options: " . \App\Models\AcademicOption::count() . "\n";
        
        echo "\n🎉 DIAGNOSTIC TERMINÉ!\n";
    }
}
