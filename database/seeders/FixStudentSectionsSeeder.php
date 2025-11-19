<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\ExamRecord;

class FixStudentSectionsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔧 Correction des sections des étudiants...\n";
        
        // Récupérer les étudiants de la classe 6 (Maternelle 3ème Année)
        $students = StudentRecord::where('my_class_id', 6)->with('user')->get();
        $sections = [21, 22, 23, 24]; // A, B, C, D
        
        echo "Étudiants trouvés: " . $students->count() . "\n";
        
        foreach ($students as $index => $student) {
            $sectionId = $sections[$index % 4]; // Répartir dans les 4 sections
            
            // Mettre à jour l'étudiant
            $student->update(['section_id' => $sectionId]);
            
            // Mettre à jour les notes existantes
            Mark::where('student_id', $student->user_id)
                ->where('my_class_id', 6)
                ->update(['section_id' => $sectionId]);
            
            // Mettre à jour les enregistrements d'examen
            ExamRecord::where('student_id', $student->user_id)
                ->where('my_class_id', 6)
                ->update(['section_id' => $sectionId]);
            
            $sectionName = ['A', 'B', 'C', 'D'][$index % 4];
            echo "✅ {$student->user->name} → Section {$sectionName} (ID: {$sectionId})\n";
        }
        
        echo "\n🎉 TERMINÉ! Les étudiants sont maintenant dans les sections A, B, C, D.\n";
    }
}
