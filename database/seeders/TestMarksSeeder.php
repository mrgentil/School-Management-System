<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mark;
use App\Models\ExamRecord;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class TestMarksSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        echo " Création de données de test pour la feuille de tabulation...\n";
        
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Nettoyer les anciennes données de test
        Mark::truncate();
        ExamRecord::truncate();
        
        // Récupérer les données existantes
        $exam = Exam::first();
        $classes = MyClass::with('class_type')->take(3)->get();
        $subjects = Subject::take(5)->get();
        
        if (!$exam || $classes->isEmpty() || $subjects->isEmpty()) {
            echo " Erreur: Pas assez de données de base (examens, classes, matières)\n";
            return;
        }
        
        echo " Examen: {$exam->name}\n";
        echo " Matières: " . $subjects->pluck('name')->join(', ') . "\n";
        
        $totalMarks = 0;
        $totalRecords = 0;
        
        foreach ($classes as $class) {
            echo "\n Classe: {$class->name} ({$class->class_type->name})\n";
            
            // Récupérer les étudiants de cette classe
            $students = StudentRecord::where('my_class_id', $class->id)
                ->with('user')
                ->take(8) // Limiter à 8 étudiants par classe
                ->get();
            
            if ($students->isEmpty()) {
                echo "   Aucun étudiant trouvé dans cette classe\n";
                continue;
            }
            
            echo "   Étudiants: " . $students->count() . "\n";
            
            foreach ($students as $student) {
                $studentTotal = 0;
                $subjectCount = 0;
                
                foreach ($subjects as $subject) {
                    // Générer une note réaliste (entre 8 et 20)
                    $score = rand(8, 20);
                    
                    // Créer la note
                    Mark::create([
                        'student_id' => $student->user_id,
                        'subject_id' => $subject->id,
                        'my_class_id' => $class->id,
                        'section_id' => $student->section_id,
                        'exam_id' => $exam->id,
                        'tex1' => $score, // Note d'examen
                        'year' => date('Y')
                    ]);
                    
                    $studentTotal += $score;
                    $subjectCount++;
                    $totalMarks++;
                }
                
                // Calculer la moyenne
                $average = $subjectCount > 0 ? round($studentTotal / $subjectCount, 2) : 0;
                
                // Créer l'enregistrement d'examen pour ce student
                ExamRecord::create([
                    'student_id' => $student->user_id,
                    'my_class_id' => $class->id,
                    'section_id' => $student->section_id,
                    'exam_id' => $exam->id,
                    'total' => $studentTotal,
                    'ave' => $average,
                    'class_ave' => 0, // Sera calculé après
                    'pos' => 0, // Sera calculé après
                    'year' => date('Y')
                ]);
                
                $totalRecords++;
                echo "   {$student->user->name}: {$subjectCount} notes, Total: {$studentTotal}, Moyenne: {$average}\n";
            }
            
            // Calculer les positions pour cette classe
            $this->calculatePositions($exam->id, $class->id);
        }
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "\n TERMINÉ!\n";
        echo " Total: {$totalMarks} notes créées pour {$totalRecords} étudiants\n";
        echo " URL de test: http://localhost:8000/marks/tabulation/{$exam->id}\n";
    }
    
    /**
     * Calculer les positions/classements pour une classe
     */
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
        
        // Calculer la moyenne de classe
        $classAverage = $records->avg('ave');
        ExamRecord::where('exam_id', $examId)
            ->where('my_class_id', $classId)
            ->update(['class_ave' => round($classAverage, 2)]);
    }
}
