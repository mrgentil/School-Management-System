<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\User;

class AddMoreSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🎯 AJOUT DE MATIÈRES SUPPLÉMENTAIRES...\n\n";
        
        // Récupérer la première classe (6ème Sec A Electronique)
        $class = MyClass::first();
        if (!$class) {
            echo "❌ Aucune classe trouvée\n";
            return;
        }
        
        echo "🏫 Classe: " . ($class->full_name ?: $class->name) . "\n";
        
        // Récupérer un enseignant
        $teacher = User::where('user_type', 'teacher')->first();
        
        // Matières supplémentaires pour l'électronique
        $additionalSubjects = [
            'Mathématiques',
            'Physique',
            'Chimie',
            'Français',
            'Anglais',
            'Électronique Générale',
            'Circuits Électriques'
        ];
        
        $created = 0;
        
        foreach ($additionalSubjects as $subjectName) {
            // Vérifier si la matière existe déjà
            $exists = Subject::where('my_class_id', $class->id)
                            ->where('name', $subjectName)
                            ->exists();
            
            if (!$exists) {
                Subject::create([
                    'name' => $subjectName,
                    'slug' => \Illuminate\Support\Str::slug($subjectName),
                    'my_class_id' => $class->id,
                    'teacher_id' => $teacher->id
                ]);
                
                echo "   ✅ {$subjectName} ajoutée\n";
                $created++;
            } else {
                echo "   ⚠️ {$subjectName} existe déjà\n";
            }
        }
        
        echo "\n📊 RÉSUMÉ:\n";
        echo "   ├─ Matières ajoutées: {$created}\n";
        
        $totalSubjects = Subject::where('my_class_id', $class->id)->count();
        echo "   └─ Total matières dans la classe: {$totalSubjects}\n\n";
        
        echo "🎯 MAINTENANT TESTEZ:\n";
        echo "   ├─ 1️⃣ Allez sur: http://localhost:8000/subject-grades-config\n";
        echo "   ├─ 2️⃣ Sélectionnez '" . ($class->full_name ?: $class->name) . "'\n";
        echo "   ├─ 3️⃣ Vous devriez voir {$totalSubjects} matières dans le tableau\n";
        echo "   ├─ 4️⃣ Configurez les cotes (ex: Période 20, Examen 40)\n";
        echo "   └─ 5️⃣ Cliquez sur 'Sauvegarder la Configuration'\n\n";
        
        echo "🎉 PRÊT POUR LES TESTS!\n";
    }
}
