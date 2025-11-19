<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;

class DebugClassDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DEBUG DES DONNÉES DE CLASSES POUR ASSIGNMENT...\n\n";
        
        // Simuler ce que le contrôleur charge
        $classes = MyClass::with(['academicSection', 'option', 'section'])
            ->orderBy('name')
            ->get();
            
        echo "📋 CLASSES CHARGÉES AVEC LEURS ATTRIBUTS DATA:\n\n";
        
        foreach ($classes as $class) {
            echo "Classe: {$class->name}\n";
            echo "├─ ID: {$class->id}\n";
            echo "├─ Nom complet: " . ($class->full_name ?: 'N/A') . "\n";
            echo "├─ data-section: " . ($class->academicSection ? $class->academicSection->name : '') . "\n";
            echo "├─ data-option: " . ($class->option ? $class->option->name : '') . "\n";
            
            // Simuler la détection de type
            $section = $class->academicSection ? $class->academicSection->name : '';
            $classType = 'Primaire'; // Par défaut
            
            if ($section) {
                $classType = $section;
            } else {
                $lowerName = strtolower($class->name);
                if (strpos($lowerName, 'maternelle') !== false || strpos($lowerName, 'crèche') !== false) {
                    $classType = 'Maternelle';
                } elseif (strpos($lowerName, 'primaire') !== false) {
                    $classType = 'Primaire';
                } elseif (strpos($lowerName, 'technique') !== false) {
                    $classType = 'Technique';
                } elseif (strpos($lowerName, 'commercial') !== false) {
                    $classType = 'Commercial';
                } elseif (strpos($lowerName, 'scientifique') !== false) {
                    $classType = 'Scientifique';
                }
            }
            
            echo "└─ Type détecté: {$classType}\n";
            
            // Matières suggérées
            $subjectsByType = [
                'Technique' => ['Mathématiques', 'Physique', 'Électronique', 'Mécanique', 'Informatique', 'Français', 'Anglais'],
                'Commercial' => ['Mathématiques', 'Comptabilité', 'Économie', 'Gestion', 'Français', 'Anglais'],
                'Scientifique' => ['Mathématiques', 'Physique', 'Chimie', 'Biologie', 'Français', 'Anglais'],
                'Litteraire' => ['Français', 'Anglais', 'Histoire', 'Géographie', 'Philosophie'],
                'Maternelle' => ['Jeux Éducatifs', 'Éveil', 'Motricité', 'Langage'],
                'Primaire' => ['Mathématiques', 'Français', 'Sciences', 'Histoire', 'Géographie', 'Anglais']
            ];
            
            $relevantSubjects = $subjectsByType[$classType] ?? [];
            echo "   Matières suggérées: " . implode(', ', $relevantSubjects) . "\n";
            echo "\n";
        }
        
        echo "🎯 CLASSES SPÉCIFIQUES À TESTER:\n";
        $testClasses = ['6ème Sec D Informatique', 'Maternelle 3ème Année', '3ème Secondaire Technique'];
        
        foreach ($testClasses as $testName) {
            $class = $classes->where('name', 'LIKE', "%{$testName}%")->first();
            if ($class) {
                echo "✅ {$testName} → Section: " . ($class->academicSection ? $class->academicSection->name : 'N/A') . "\n";
            } else {
                echo "❌ {$testName} → Non trouvée\n";
            }
        }
        
        echo "\n🔧 VÉRIFICATIONS:\n";
        echo "1. Ouvre la page /assignments/create\n";
        echo "2. Ouvre la console du navigateur (F12)\n";
        echo "3. Sélectionne une classe\n";
        echo "4. Vérifie les messages de debug\n";
        echo "5. Vérifie si l'alert s'affiche\n";
    }
}
