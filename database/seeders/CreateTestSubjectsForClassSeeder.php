<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\User;

class CreateTestSubjectsForClassSeeder extends Seeder
{
    public function run(): void
    {
        echo "🎯 CRÉATION DE MATIÈRES DE TEST POUR LES CLASSES...\n\n";
        
        // Récupérer les classes existantes
        $classes = MyClass::all();
        echo "📋 Classes trouvées: " . $classes->count() . "\n";
        
        if ($classes->count() == 0) {
            echo "❌ Aucune classe trouvée. Créons d'abord des classes de test.\n";
            return;
        }
        
        // Matières standard RDC
        $standardSubjects = [
            'Français',
            'Anglais', 
            'Mathématiques',
            'Sciences',
            'Histoire',
            'Géographie',
            'Éducation Civique',
            'Éducation Physique'
        ];
        
        // Récupérer un enseignant pour assigner aux matières
        $teacher = User::where('user_type', 'teacher')->first();
        if (!$teacher) {
            // Créer un enseignant de test
            $teacher = User::create([
                'name' => 'Professeur Test',
                'email' => 'prof.test@school.cd',
                'user_type' => 'teacher',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            echo "👨‍🏫 Enseignant de test créé: {$teacher->name}\n";
        }
        
        $totalCreated = 0;
        
        foreach ($classes as $class) {
            echo "\n🏫 Classe: " . ($class->full_name ?: $class->name) . "\n";
            
            // Vérifier les matières existantes pour cette classe
            $existingSubjects = Subject::where('my_class_id', $class->id)->get();
            echo "   ├─ Matières existantes: " . $existingSubjects->count() . "\n";
            
            if ($existingSubjects->count() > 0) {
                echo "   ├─ Matières: " . $existingSubjects->pluck('name')->implode(', ') . "\n";
            } else {
                echo "   ├─ Aucune matière trouvée. Création en cours...\n";
                
                // Créer les matières standard pour cette classe
                foreach ($standardSubjects as $subjectName) {
                    $subject = Subject::create([
                        'name' => $subjectName,
                        'slug' => \Illuminate\Support\Str::slug($subjectName),
                        'my_class_id' => $class->id,
                        'teacher_id' => $teacher->id
                    ]);
                    
                    echo "   │  ├─ ✅ {$subjectName} créée\n";
                    $totalCreated++;
                }
            }
        }
        
        echo "\n📊 RÉSUMÉ:\n";
        echo "   ├─ Classes traitées: " . $classes->count() . "\n";
        echo "   ├─ Matières créées: {$totalCreated}\n";
        echo "   └─ Enseignant assigné: {$teacher->name}\n\n";
        
        echo "🧪 VÉRIFICATION FINALE:\n";
        foreach ($classes as $class) {
            $subjects = Subject::where('my_class_id', $class->id)->get();
            echo "   ├─ " . ($class->full_name ?: $class->name) . ": " . $subjects->count() . " matières\n";
        }
        
        echo "\n🎯 MAINTENANT VOUS POUVEZ:\n";
        echo "   ├─ 1️⃣ Aller sur: http://localhost:8000/subject-grades-config\n";
        echo "   ├─ 2️⃣ Sélectionner une classe dans le dropdown\n";
        echo "   ├─ 3️⃣ Voir apparaître le tableau des matières\n";
        echo "   ├─ 4️⃣ Configurer les cotes pour chaque matière\n";
        echo "   └─ 5️⃣ Sauvegarder la configuration\n\n";
        
        echo "🎉 MATIÈRES DE TEST CRÉÉES!\n";
        echo "L'interface devrait maintenant afficher le contenu après sélection de classe!\n";
    }
}
