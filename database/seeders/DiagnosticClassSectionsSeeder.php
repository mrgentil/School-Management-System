<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Section;

class DiagnosticClassSectionsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DIAGNOSTIC DES CLASSES ET SECTIONS...\n\n";
        
        // Chercher la classe problématique
        $problematicClass = MyClass::where('name', 'LIKE', '%6ème Sec D Informatique%')->first();
        
        if ($problematicClass) {
            echo "📋 CLASSE PROBLÉMATIQUE TROUVÉE:\n";
            echo "   - Nom: {$problematicClass->name}\n";
            echo "   - ID: {$problematicClass->id}\n";
            echo "   - Division: {$problematicClass->division}\n";
            echo "   - Option: {$problematicClass->academic_option}\n\n";
            
            $sections = Section::where('my_class_id', $problematicClass->id)->get();
            echo "   - Sections: {$sections->count()}\n";
            foreach ($sections as $section) {
                echo "     * {$section->name} (ID: {$section->id})\n";
            }
        } else {
            echo "❌ Classe '6ème Sec D Informatique' non trouvée\n";
        }
        
        echo "\n📊 RÉSUMÉ GÉNÉRAL:\n";
        $classesWithoutSections = [];
        $classes = MyClass::all();
        
        foreach ($classes as $class) {
            $sectionCount = Section::where('my_class_id', $class->id)->count();
            if ($sectionCount == 0) {
                $classesWithoutSections[] = $class;
            }
        }
        
        echo "   - Total classes: " . $classes->count() . "\n";
        echo "   - Classes sans sections: " . count($classesWithoutSections) . "\n";
        
        if (!empty($classesWithoutSections)) {
            echo "\n⚠️  CLASSES SANS SECTIONS:\n";
            foreach ($classesWithoutSections as $class) {
                echo "   - {$class->name} (ID: {$class->id})\n";
            }
        }
        
        echo "\n🎉 DIAGNOSTIC TERMINÉ!\n";
    }
}
