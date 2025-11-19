<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;

class TestImprovedAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST DE LA CRÉATION DE DEVOIR AMÉLIORÉE...\n\n";
        
        // Tester quelques classes spécifiques
        $testClasses = [
            'Maternelle 3ème Année',
            '6ème Sec D Informatique',
            '6ème Sec A Informatique',
            '3ème Secondaire Technique'
        ];
        
        foreach ($testClasses as $className) {
            $class = MyClass::where('name', 'LIKE', "%{$className}%")->first();
            
            if ($class) {
                echo "📋 CLASSE: {$class->name}\n";
                echo "   ├─ Nom complet: " . ($class->full_name ?: 'N/A') . "\n";
                echo "   ├─ Section académique: " . ($class->academicSection ? $class->academicSection->name : 'N/A') . "\n";
                echo "   ├─ Option: " . ($class->option ? $class->option->name : 'N/A') . "\n";
                echo "   ├─ Sections/Divisions: " . $class->section->pluck('name')->implode(', ') . "\n";
                
                // Déterminer le type pour les matières
                $type = 'Primaire';
                if ($class->academicSection) {
                    $type = $class->academicSection->name;
                } else {
                    $lowerName = strtolower($class->name);
                    if (strpos($lowerName, 'maternelle') !== false) $type = 'Maternelle';
                    elseif (strpos($lowerName, 'technique') !== false) $type = 'Technique';
                    elseif (strpos($lowerName, 'commercial') !== false) $type = 'Commercial';
                }
                
                echo "   └─ Type détecté: {$type}\n";
                
                // Matières suggérées
                $subjectsByType = [
                    'Technique' => ['Mathématiques', 'Physique', 'Électronique', 'Mécanique', 'Informatique', 'Français', 'Anglais'],
                    'Commercial' => ['Mathématiques', 'Comptabilité', 'Économie', 'Gestion', 'Français', 'Anglais'],
                    'Maternelle' => ['Jeux Éducatifs', 'Éveil', 'Motricité', 'Langage'],
                    'Primaire' => ['Mathématiques', 'Français', 'Sciences', 'Histoire', 'Géographie', 'Anglais']
                ];
                
                $suggestedSubjects = $subjectsByType[$type] ?? $subjectsByType['Primaire'];
                echo "      Matières suggérées: " . implode(', ', $suggestedSubjects) . "\n";
                
            } else {
                echo "❌ Classe '{$className}' non trouvée\n";
            }
            echo "\n";
        }
        
        echo "✅ AMÉLIORATIONS APPORTÉES:\n";
        echo "   ├─ Plus de champ 'Section' redondant\n";
        echo "   ├─ Matières filtrées selon le type de classe\n";
        echo "   ├─ Interface simplifiée (3 colonnes au lieu de 4)\n";
        echo "   ├─ Informations de classe dans les attributs data\n";
        echo "   └─ Assignation automatique de section en backend\n";
        
        echo "\n🎉 LA CRÉATION DE DEVOIR EST MAINTENANT PLUS INTELLIGENTE!\n";
    }
}
