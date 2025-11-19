<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mark;
use App\Models\ExamRecord;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class RealDataMarksSeeder extends Seeder
{
    public function run(): void
    {
        echo "🎯 Création de notes pour les vraies classes du système...\n";
        
        // Nettoyer les anciennes données
        Mark::query()->delete();
        ExamRecord::query()->delete();
        
        // Récupérer l'examen
        $exam = Exam::first();
        if (!$exam) {
            echo "❌ Aucun examen trouvé\n";
            return;
        }
        
        // Récupérer les classes maternelle
        $classes = MyClass::where('name', 'LIKE', '%Maternelle%')
            ->orWhere('name', 'LIKE', '%maternelle%')
            ->get();
        
        echo "📚 Classes trouvées: " . $classes->count() . "\n";
        
        foreach ($classes as $class) {
            echo "\n🏫 Classe: {$class->name} (ID: {$class->id})\n";
            
            // Récupérer les étudiants de cette classe
            $students = StudentRecord::where('my_class_id', $class->id)
                ->with('user')
                ->get();
            
            if ($students->isEmpty()) {
                echo "   ⚠️  Aucun étudiant dans cette classe\n";
                continue;
            }
            
            // Récupérer les matières de cette classe
            $subjects = Subject::where('my_class_id', $class->id)->get();
            
            if ($subjects->isEmpty()) {
                echo "   ⚠️  Aucune matière pour cette classe\n";
                continue;
            }
            
            echo "   👥 Étudiants: " . $students->count() . "\n";
            echo "   📖 Matières: " . $subjects->count() . " (" . $subjects->pluck('name')->join(', ') . ")\n";
            
            foreach ($students as $student) {
                $studentTotal = 0;
                $subjectCount = 0;
                
                foreach ($subjects as $subject) {
                    // Générer une note réaliste
                    $score = rand(10, 20);
                    
                    // Créer la note
                    Mark::create([
                        'student_id' => $student->user_id,
                        'subject_id' => $subject->id,
                        'my_class_id' => $class->id,
                        'section_id' => $student->section_id,
                        'exam_id' => $exam->id,
                        'tex1' => $score,
                        'year' => date('Y')
                    ]);
                    
                    $studentTotal += $score;
                    $subjectCount++;
                }
                
                if ($subjectCount > 0) {
                    $average = round($studentTotal / $subjectCount, 2);
                    
                    // Créer l'enregistrement d'examen
                    ExamRecord::create([
                        'student_id' => $student->user_id,
                        'my_class_id' => $class->id,
                        'section_id' => $student->section_id,
                        'exam_id' => $exam->id,
                        'total' => $studentTotal,
                        'ave' => $average,
                        'class_ave' => 0,
                        'pos' => 0,
                        'year' => date('Y')
                    ]);
                    
                    echo "   ✅ {$student->user->name}: {$subjectCount} notes, Total: {$studentTotal}\n";
                }
            }
            
            // Calculer les positions
            $this->calculatePositions($exam->id, $class->id);
        }
        
        echo "\n🎉 TERMINÉ! Notes créées pour les vraies classes.\n";
    }
    
    private function calculatePositions($examId, $classId)
    {
        $records = ExamRecord::where('exam_id', $examId)
            ->where('my_class_id', $classId)
            ->orderBy('total', 'desc')
            ->get();
        
        $position = 1;
        foreach ($records as $record) {
            $record->update(['pos' => $position]);
            $position++;
        }
        
        $classAverage = $records->avg('ave');
        ExamRecord::where('exam_id', $examId)
            ->where('my_class_id', $classId)
            ->update(['class_ave' => round($classAverage, 2)]);
    }
}
