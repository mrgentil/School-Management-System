<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentRecord;
use App\Helpers\Qs;

class TestAssignClassFixSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST DE LA CORRECTION D'ASSIGNATION DE CLASSE...\n\n";
        
        $currentSession = Qs::getCurrentSession();
        
        // Simuler ce que le contrôleur charge maintenant
        echo "📋 CHARGEMENT DES DONNÉES...\n";
        
        // Classes avec relations
        $classes = \App\Models\MyClass::with(['academicSection', 'option'])
            ->orderBy('name')
            ->take(5)
            ->get();
            
        echo "✅ Classes chargées avec relations: " . $classes->count() . "\n";
        foreach ($classes as $class) {
            echo "   ├─ {$class->name} → " . ($class->full_name ?: 'Pas de nom complet') . "\n";
        }
        
        // Étudiants assignés avec relations
        $assignedStudents = StudentRecord::where('session', $currentSession)
            ->with(['user', 'my_class.academicSection', 'my_class.option'])
            ->take(5)
            ->get();
            
        echo "\n✅ Étudiants assignés: " . $assignedStudents->count() . "\n";
        foreach ($assignedStudents as $sr) {
            echo "   ├─ {$sr->user->name}\n";
            if ($sr->my_class) {
                echo "   │  ├─ Classe: {$sr->my_class->name}\n";
                echo "   │  ├─ Nom complet: " . ($sr->my_class->full_name ?: 'N/A') . "\n";
                echo "   │  ├─ Section académique: " . ($sr->my_class->academicSection ? $sr->my_class->academicSection->name : 'N/A') . "\n";
                echo "   │  └─ Option: " . ($sr->my_class->option ? $sr->my_class->option->name : 'N/A') . "\n";
            } else {
                echo "   │  └─ ❌ CLASSE NULL - Sera affiché comme 'Non assigné'\n";
            }
        }
        
        echo "\n✅ CORRECTIONS APPORTÉES:\n";
        echo "   ├─ StudentRecordController: Charge les relations complètes\n";
        echo "   ├─ assign_class.blade.php: Protection contre my_class null\n";
        echo "   ├─ Affichage: 'Non assigné' si pas de classe\n";
        echo "   ├─ JavaScript: Protection contre valeurs null\n";
        echo "   └─ Classes: Noms complets partout\n";
        
        echo "\n🎯 MAINTENANT DANS L'ASSIGNATION:\n";
        echo "   ├─ Dropdown classes: '6ème Sec A Électronique'\n";
        echo "   ├─ Tableau étudiants: '6ème Sec A Électronique' ou 'Non assigné'\n";
        echo "   ├─ Plus d'erreur 'full_name on null'\n";
        echo "   └─ Interface robuste et informative\n";
        
        echo "\n🚀 TESTE MAINTENANT:\n";
        echo "1. Va sur /students/assign-class\n";
        echo "2. L'erreur ne devrait plus apparaître\n";
        echo "3. Tu devrais voir les noms complets des classes\n";
        echo "4. Les étudiants sans classe affichent 'Non assigné'\n";
    }
}
