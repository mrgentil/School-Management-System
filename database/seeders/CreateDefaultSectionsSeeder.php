<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Section;

class CreateDefaultSectionsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🏫 Création de sections par défaut pour toutes les classes...\n";
        
        $classes = MyClass::all();
        $sectionsCreated = 0;
        
        foreach ($classes as $class) {
            // Vérifier si la classe a déjà des sections
            $existingSections = Section::where('my_class_id', $class->id)->count();
            
            if ($existingSections == 0) {
                // Créer une section par défaut "A" pour cette classe
                Section::create([
                    'name' => 'A',
                    'my_class_id' => $class->id,
                    'active' => 1,
                    'teacher_id' => null, // Pas d'enseignant assigné par défaut
                ]);
                
                echo "✅ Section 'A' créée pour la classe: {$class->name}\n";
                $sectionsCreated++;
            } else {
                echo "ℹ️  Classe {$class->name} a déjà {$existingSections} section(s)\n";
            }
        }
        
        echo "\n🎉 TERMINÉ! {$sectionsCreated} sections créées.\n";
        echo "📊 Total des classes: " . $classes->count() . "\n";
    }
}
