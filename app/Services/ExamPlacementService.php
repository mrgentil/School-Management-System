<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamRoom;
use App\Models\ExamSchedule;
use App\Models\ExamStudentPlacement;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\ExamRecord;
use Illuminate\Support\Facades\DB;

class ExamPlacementService
{
    /**
     * Placer automatiquement les étudiants pour un examen SESSION
     * Classe les étudiants par performance et les répartit dans les salles A, B, C
     * 
     * @param int $exam_schedule_id
     * @return array Statistiques du placement
     */
    public function placeStudentsForSession($exam_schedule_id)
    {
        $schedule = ExamSchedule::with(['exam', 'my_class', 'section', 'subject'])->findOrFail($exam_schedule_id);
        
        // Vérifier que c'est bien un examen SESSION
        if ($schedule->exam->exam_type !== 'session') {
            throw new \Exception("Cette méthode est uniquement pour les examens SESSION");
        }

        DB::beginTransaction();
        try {
            // 1. Supprimer les placements existants
            ExamStudentPlacement::where('exam_schedule_id', $exam_schedule_id)->delete();

            // 2. Récupérer tous les étudiants concernés (même matière, mélange de classes)
            $students = $this->getStudentsForPlacement($schedule);

            // 3. Calculer le score de performance pour chaque étudiant
            $studentsWithScores = $this->calculateStudentScores($students, $schedule);

            // 4. Trier par performance (meilleurs en premier)
            $sortedStudents = $studentsWithScores->sortByDesc('ranking_score');

            // 5. Récupérer les salles disponibles (A = excellence, B = moyen, C = faible)
            $rooms = ExamRoom::active()->orderBy('level', 'asc')->get();

            if ($rooms->isEmpty()) {
                throw new \Exception("Aucune salle d'examen disponible");
            }

            // 6. Répartir les étudiants dans les salles
            $placements = $this->distributeStudentsToRooms($sortedStudents, $rooms, $exam_schedule_id);

            // 7. Sauvegarder les placements
            foreach ($placements as $placement) {
                ExamStudentPlacement::create($placement);
            }

            DB::commit();

            return [
                'success' => true,
                'total_students' => $sortedStudents->count(),
                'placements_created' => count($placements),
                'rooms_used' => collect($placements)->pluck('exam_room_id')->unique()->count(),
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Récupérer les étudiants qui passent cet examen
     * Pour SESSION: tous les étudiants des classes qui ont cette matière
     */
    protected function getStudentsForPlacement($schedule)
    {
        // Récupérer toutes les classes du même niveau (par example toutes les JSS2)
        $classType = $schedule->my_class->class_type;
        
        // Récupérer tous les étudiants de ce niveau qui ont cette matière
        return StudentRecord::whereHas('my_class', function($q) use ($classType) {
            $q->where('class_type_id', $classType->id);
        })
        ->whereHas('my_class.subjects', function($q) use ($schedule) {
            $q->where('subjects.id', $schedule->subject_id);
        })
        ->with(['user', 'my_class', 'section'])
        ->get();
    }

    /**
     * Calculer le score de performance pour chaque étudiant
     * Basé sur la moyenne générale de la période précédente
     */
    protected function calculateStudentScores($students, $schedule)
    {
        $current_year = $schedule->exam->year;
        $semester = $schedule->exam->semester;

        return $students->map(function($studentRecord) use ($current_year, $semester) {
            // Calculer la moyenne générale basée sur les périodes précédentes
            $score = $this->calculateStudentAverageScore($studentRecord->user_id, $current_year, $semester);
            
            $studentRecord->ranking_score = $score;
            $studentRecord->performance_level = $this->determinePerformanceLevel($score);
            
            return $studentRecord;
        });
    }

    /**
     * Calculer la moyenne de l'étudiant
     * Basé sur les PÉRIODES du semestre en cours uniquement
     */
    protected function calculateStudentAverageScore($student_id, $year, $semester)
    {
        // Récupérer les moyennes de périodes pour ce semestre
        $marks = Mark::where('student_id', $student_id)
            ->where('year', $year)
            ->get();

        if ($marks->isEmpty()) {
            return 0; // Aucune donnée
        }

        // Calculer moyenne des périodes du semestre en cours
        $periodAvgs = [];
        if ($semester == 1) {
            // Semestre 1 = Périodes 1 & 2
            $periodAvgs = [
                $marks->avg('p1_avg'),
                $marks->avg('p2_avg')
            ];
        } else {
            // Semestre 2 = Périodes 3 & 4
            $periodAvgs = [
                $marks->avg('p3_avg'),
                $marks->avg('p4_avg')
            ];
        }

        // Moyenne des deux périodes
        $avg = collect($periodAvgs)->filter()->avg();
        
        return $avg ?? 0;
    }

    /**
     * Déterminer le niveau de performance
     */
    protected function determinePerformanceLevel($score)
    {
        if ($score >= 70) {
            return 'excellence';
        } elseif ($score >= 50) {
            return 'moyen';
        } else {
            return 'faible';
        }
    }

    /**
     * Distribuer les étudiants dans les salles
     * Salle A = Top performers, Salle B = Moyens, Salle C = Faibles
     */
    protected function distributeStudentsToRooms($sortedStudents, $rooms, $exam_schedule_id)
    {
        $placements = [];
        $totalStudents = $sortedStudents->count();
        
        // Répartir par tiers
        $excellenceCount = ceil($totalStudents * 0.30); // 30% meilleurs
        $moyenCount = ceil($totalStudents * 0.40); // 40% moyens
        // Le reste = faibles (30%)

        $studentIndex = 0;
        $currentRoom = null;
        $seatNumber = 1;

        foreach ($sortedStudents as $student) {
            // Déterminer le niveau actuel
            if ($studentIndex < $excellenceCount) {
                $level = 'excellence';
            } elseif ($studentIndex < ($excellenceCount + $moyenCount)) {
                $level = 'moyen';
            } else {
                $level = 'faible';
            }

            // Trouver une salle du niveau approprié
            if (!$currentRoom || $currentRoom->level !== $level || $seatNumber > $currentRoom->capacity) {
                $currentRoom = $rooms->where('level', $level)->first();
                $seatNumber = 1;
                
                if (!$currentRoom) {
                    // Fallback: prendre n'importe quelle salle disponible
                    $currentRoom = $rooms->first();
                }
            }

            $placements[] = [
                'exam_schedule_id' => $exam_schedule_id,
                'student_id' => $student->user_id,
                'exam_room_id' => $currentRoom->id,
                'seat_number' => $seatNumber,
                'ranking_score' => $student->ranking_score,
                'performance_level' => $level,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $seatNumber++;
            $studentIndex++;
        }

        return $placements;
    }

    /**
     * Obtenir la liste des étudiants placés par salle
     */
    public function getPlacementsByRoom($exam_schedule_id)
    {
        return ExamStudentPlacement::where('exam_schedule_id', $exam_schedule_id)
            ->with(['student.student_record.my_class', 'student.student_record.section', 'room'])
            ->orderBy('exam_room_id')
            ->orderBy('seat_number')
            ->get()
            ->groupBy('exam_room_id');
    }

    /**
     * Obtenir le placement d'un étudiant spécifique
     */
    public function getStudentPlacement($exam_schedule_id, $student_id)
    {
        return ExamStudentPlacement::where('exam_schedule_id', $exam_schedule_id)
            ->where('student_id', $student_id)
            ->with(['room', 'schedule.subject'])
            ->first();
    }
}
