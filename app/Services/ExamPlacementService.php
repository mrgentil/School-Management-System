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
     * Placer automatiquement les étudiants pour un examen SESSION complet
     * LOGIQUE CORRECTE: Un élève a UNE salle et UN numéro de place pour TOUT l'examen SESSION
     * Classe les étudiants par performance et les répartit dans les salles A, B, C
     * 
     * @param int $exam_id
     * @return array Statistiques du placement
     */
    public function placeStudentsForSession($exam_id)
    {
        $exam = Exam::with('schedules')->findOrFail($exam_id);
        
        // Vérifier qu'il y a au moins un horaire SESSION
        $hasSessionSchedules = $exam->schedules->where('exam_type', 'session')->count() > 0;
        if (!$hasSessionSchedules) {
            throw new \Exception("Cet examen n'a aucun horaire de type SESSION");
        }

        DB::beginTransaction();
        try {
            // 1. Supprimer les placements existants pour cet examen
            ExamStudentPlacement::where('exam_id', $exam_id)->delete();

            // 2. Récupérer tous les étudiants concernés par les horaires SESSION de cet examen
            $students = $this->getStudentsForExam($exam);
            
            if ($students->isEmpty()) {
                throw new \Exception("Aucun étudiant trouvé pour cet examen");
            }

            // 3. Calculer le score de performance pour chaque étudiant
            $studentsWithScores = $this->calculateStudentScores($students, $exam);

            // 4. Trier par performance (meilleurs en premier)
            $sortedStudents = $studentsWithScores->sortByDesc('ranking_score');

            // 5. Récupérer les salles disponibles (A = excellence, B = moyen, C = faible)
            $rooms = ExamRoom::active()->orderBy('level', 'asc')->get();

            if ($rooms->isEmpty()) {
                throw new \Exception("Aucune salle d'examen disponible");
            }

            // 6. Répartir les étudiants dans les salles
            $placements = $this->distributeStudentsToRooms($sortedStudents, $rooms, $exam_id);

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
     * Récupérer tous les étudiants qui passent cet examen SESSION
     * LOGIQUE: Tous les élèves sont mélangés (toutes classes, toutes options)
     * et classés uniquement par performance globale
     */
    protected function getStudentsForExam($exam)
    {
        // Récupérer tous les horaires SESSION de l'examen
        $sessionSchedules = $exam->schedules->where('exam_type', 'session');
        
        if ($sessionSchedules->isEmpty()) {
            return collect([]);
        }
        
        // Récupérer TOUTES les classes concernées par les horaires SESSION
        $classIds = $sessionSchedules->pluck('my_class_id')->unique()->filter()->toArray();
        
        if (empty($classIds)) {
            return collect([]);
        }
        
        // Récupérer TOUS les étudiants de ces classes
        // PAS de filtrage par option_id ou section_id
        // Dans un examen SESSION, on mélange tout le monde et on classe par performance
        // Exemple: Salle A peut contenir JSS2A, JSS3, JSS2 Technique, etc.
        return StudentRecord::whereIn('my_class_id', $classIds)
            ->where('year', $exam->year)
            ->with(['user', 'my_class', 'section', 'option'])
            ->get();
    }

    /**
     * Calculer le score de performance pour chaque étudiant
     * Basé sur la moyenne générale de la période précédente
     */
    protected function calculateStudentScores($students, $exam)
    {
        $current_year = $exam->year;
        $semester = $exam->semester;

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
     * LOGIQUE: Tous les élèves (toutes classes/options mélangées) sont classés par performance
     * et distribués dans les salles A (excellence), B (moyen), C (faible)
     * Respecte les capacités des salles et distribue équitablement
     */
    protected function distributeStudentsToRooms($sortedStudents, $rooms, $exam_id)
    {
        $placements = [];
        $totalStudents = $sortedStudents->count();
        
        // Répartir par niveau de performance
        $excellenceCount = ceil($totalStudents * 0.30); // 30% meilleurs
        $moyenCount = ceil($totalStudents * 0.40); // 40% moyens
        // Le reste = faibles (30%)

        // Grouper les salles par niveau et préparer les compteurs
        $excellenceRooms = $rooms->where('level', 'excellence')->values();
        $moyenRooms = $rooms->where('level', 'moyen')->values();
        $faibleRooms = $rooms->where('level', 'faible')->values();

        // Compteurs pour chaque salle
        $roomCounters = [];
        foreach ($rooms as $room) {
            $roomCounters[$room->id] = 0;
        }

        $studentIndex = 0;

        foreach ($sortedStudents as $student) {
            // Déterminer le niveau de performance
            if ($studentIndex < $excellenceCount) {
                $targetRooms = $excellenceRooms;
                $level = 'excellence';
            } elseif ($studentIndex < ($excellenceCount + $moyenCount)) {
                $targetRooms = $moyenRooms;
                $level = 'moyen';
            } else {
                $targetRooms = $faibleRooms;
                $level = 'faible';
            }

            // Trouver la salle avec le moins d'élèves dans ce niveau
            $selectedRoom = null;
            $minCount = PHP_INT_MAX;
            
            foreach ($targetRooms as $room) {
                if ($roomCounters[$room->id] < $room->capacity && $roomCounters[$room->id] < $minCount) {
                    $selectedRoom = $room;
                    $minCount = $roomCounters[$room->id];
                }
            }

            // Si aucune salle disponible dans le niveau, utiliser n'importe quelle salle
            if (!$selectedRoom) {
                foreach ($rooms as $room) {
                    if ($roomCounters[$room->id] < $room->capacity) {
                        $selectedRoom = $room;
                        break;
                    }
                }
            }

            // Fallback absolu
            if (!$selectedRoom) {
                $selectedRoom = $rooms->first();
            }

            // Incrémenter le compteur de la salle
            $roomCounters[$selectedRoom->id]++;

            $placements[] = [
                'exam_id' => $exam_id,
                'student_id' => $student->user_id,
                'exam_room_id' => $selectedRoom->id,
                'seat_number' => $roomCounters[$selectedRoom->id],
                'ranking_score' => $student->ranking_score,
                'performance_level' => $level,
            ];

            $studentIndex++;
        }

        return $placements;
    }

    /**
     * Obtenir les placements groupés par salle pour un examen
     */
    public function getPlacementsByRoom($exam_id)
    {
        $placements = ExamStudentPlacement::where('exam_id', $exam_id)
            ->with(['student', 'room', 'studentRecord.my_class'])
            ->orderBy('exam_room_id')
            ->orderBy('seat_number')
            ->get();
        
        return $placements->groupBy('exam_room_id');
    }

    /**
     * Obtenir le placement d'un étudiant spécifique pour un examen
     */
    public function getStudentPlacement($exam_id, $student_id)
    {
        return ExamStudentPlacement::where('exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->with(['room', 'exam'])
            ->first();
    }
}
