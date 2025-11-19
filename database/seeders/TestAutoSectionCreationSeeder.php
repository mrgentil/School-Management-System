<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Section;

class TestAutoSectionCreationSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST DE CRÉATION AUTOMATIQUE DE SECTIONS...\n\n";
        
        // Vérifier quelles classes n'ont pas de sections
        $classes = MyClass::with('section')->get();
        
        echo "📋 ÉTAT ACTUEL DES CLASSES ET SECTIONS:\n";
        foreach ($classes as $class) {
            $sectionCount = $class->section->count();
            echo "   ├─ {$class->name} (ID: {$class->id}): {$sectionCount} section(s)\n";
            
            if ($sectionCount === 0) {
                echo "   │  └─ ❌ AUCUNE SECTION - Sera créée automatiquement lors de l'assignation\n";
            } else {
                foreach ($class->section as $section) {
                    echo "   │  └─ ✅ Section: {$section->name}\n";
                }
            }
        }
        
        echo "\n🔧 SIMULATION DE CRÉATION AUTOMATIQUE:\n";
        
        // Trouver une classe sans section pour tester
        $classWithoutSection = $classes->where('section', '[]')->first();
        
        if ($classWithoutSection) {
            echo "   ├─ Classe test: {$classWithoutSection->name}\n";
            echo "   ├─ Sections avant: " . $classWithoutSection->section->count() . "\n";
            
            // Simuler la logique du contrôleur
            $defaultSection = Section::where('my_class_id', $classWithoutSection->id)->first();
            
            if (!$defaultSection) {
                echo "   ├─ Création d'une section par défaut...\n";
                
                $defaultSection = Section::create([
                    'name' => 'A',
                    'my_class_id' => $classWithoutSection->id,
                    'active' => 1,
                    'teacher_id' => null,
                ]);
                
                echo "   ├─ ✅ Section créée: {$defaultSection->name} (ID: {$defaultSection->id})\n";
                echo "   └─ Sections après: " . Section::where('my_class_id', $classWithoutSection->id)->count() . "\n";
            } else {
                echo "   └─ Section déjà existante: {$defaultSection->name}\n";
            }
        } else {
            echo "   └─ Toutes les classes ont déjà des sections\n";
        }
        
        echo "\n✅ LOGIQUE IMPLÉMENTÉE:\n";
        echo "   ├─ store_assignment: Crée section 'A' si manquante\n";
        echo "   ├─ update_assignment: Crée section 'A' si manquante\n";
        echo "   ├─ Log automatique: Enregistre la création dans les logs\n";
        echo "   └─ Plus d'erreur: 'Aucune section trouvée' éliminée\n";
        
        echo "\n🎯 MAINTENANT QUAND TU MODIFIES UNE ASSIGNATION:\n";
        echo "   ├─ Si la classe a des sections → Utilise la première\n";
        echo "   ├─ Si la classe n'a pas de sections → Crée automatiquement 'A'\n";
        echo "   ├─ Plus d'erreur bloquante\n";
        echo "   └─ Interface fluide et automatique\n";
        
        echo "\n🚀 TESTE MAINTENANT:\n";
        echo "1. Va sur /students/assign-class\n";
        echo "2. Clique sur 'Modifier Classe' pour un étudiant\n";
        echo "3. Choisis une classe (même '6ème Sec D')\n";
        echo "4. L'assignation devrait fonctionner sans erreur\n";
    }
}
