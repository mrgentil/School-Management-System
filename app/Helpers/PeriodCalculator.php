<?php

namespace App\Helpers;

use App\Models\Assignment\AssignmentSubmission;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;

class PeriodCalculator
{
    /**
     * Calculer la moyenne d'une période pour un étudiant dans une matière
     * 
     * @param int $student_id
     * @param int $subject_id
     * @param int $period (1, 2, 3, ou 4)
     * @param int $class_id
     * @param string $year
     * @return float|null
     */
    public static function calculatePeriodAverage($student_id, $subject_id, $period, $class_id, $year)
    {
        // Récupérer toutes les soumissions de devoirs pour cette période
        $submissions = AssignmentSubmission::whereHas('assignment', function($query) use ($subject_id, $period, $class_id) {
            $query->where('subject_id', $subject_id)
                  ->where('period', $period)
                  ->where('my_class_id', $class_id)
                  ->where('status', 'active');
        })
        ->where('student_id', $student_id)
        ->where('status', 'graded')
        ->whereNotNull('score')
        ->get();

        if ($submissions->isEmpty()) {
            return null;
        }

        // Calculer le pourcentage moyen (toutes les notes ramenées sur 20)
        $totalPercentage = 0;
        $count = 0;

        foreach ($submissions as $submission) {
            $assignment = $submission->assignment;
            // Ramener la note sur 20
            $percentage = ($submission->score / $assignment->max_score) * 20;
            $totalPercentage += $percentage;
            $count++;
        }

        return $count > 0 ? round($totalPercentage / $count, 2) : null;
    }

    /**
     * Mettre à jour la moyenne d'une période dans la table marks
     * 
     * @param int $student_id
     * @param int $subject_id
     * @param int $period
     * @param int $class_id
     * @param int $section_id
     * @param string $year
     * @return void
     */
    public static function updatePeriodAverageInMarks($student_id, $subject_id, $period, $class_id, $section_id, $year)
    {
        $average = self::calculatePeriodAverage($student_id, $subject_id, $period, $class_id, $year);

        if ($average === null) {
            return; // Pas de notes, on ne fait rien
        }

        // Déterminer la colonne à mettre à jour
        $columnName = 'p' . $period . '_avg';

        // Chercher ou créer l'enregistrement dans marks
        $mark = Mark::firstOrCreate(
            [
                'student_id' => $student_id,
                'subject_id' => $subject_id,
                'my_class_id' => $class_id,
                'section_id' => $section_id,
                'year' => $year,
            ],
            [
                'exam_id' => self::getOrCreateExamForPeriod($period, $year),
                'p1_avg' => null,
                'p2_avg' => null,
                'p3_avg' => null,
                'p4_avg' => null,
            ]
        );

        // Mettre à jour la moyenne de la période
        $mark->update([$columnName => $average]);
    }

    /**
     * Récupérer ou créer l'examen pour une période donnée
     * 
     * @param int $period
     * @param string $year
     * @return int
     */
    protected static function getOrCreateExamForPeriod($period, $year)
    {
        // Déterminer le semestre (période 1,2 = semestre 1, période 3,4 = semestre 2)
        $semester = $period <= 2 ? 1 : 2;

        $exam = \App\Models\Exam::firstOrCreate(
            [
                'semester' => $semester,
                'year' => $year
            ],
            [
                'name' => 'Examen Semestriel ' . $semester
            ]
        );

        return $exam->id;
    }

    /**
     * Mettre à jour toutes les moyennes de période pour un étudiant
     * Utile après modification de notes
     * 
     * @param int $student_id
     * @param int $class_id
     * @param int $section_id
     * @param string $year
     * @return void
     */
    public static function updateAllPeriodAveragesForStudent($student_id, $class_id, $section_id, $year)
    {
        // Récupérer toutes les matières de l'étudiant
        $subjects = DB::table('assignments')
            ->where('my_class_id', $class_id)
            ->where('section_id', $section_id)
            ->distinct()
            ->pluck('subject_id');

        foreach ($subjects as $subject_id) {
            for ($period = 1; $period <= 4; $period++) {
                self::updatePeriodAverageInMarks(
                    $student_id,
                    $subject_id,
                    $period,
                    $class_id,
                    $section_id,
                    $year
                );
            }
        }
    }

    /**
     * Calculer la moyenne du semestre à partir des moyennes de périodes
     * 
     * @param int $student_id
     * @param int $subject_id
     * @param int $semester (1 ou 2)
     * @param int $class_id
     * @param string $year
     * @return float|null
     */
    public static function calculateSemesterAverage($student_id, $subject_id, $semester, $class_id, $year)
    {
        $mark = Mark::where([
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'my_class_id' => $class_id,
            'year' => $year,
        ])->first();

        if (!$mark) {
            return null;
        }

        if ($semester == 1) {
            // Moyenne semestre 1 = (P1 + P2) / 2
            if ($mark->p1_avg !== null && $mark->p2_avg !== null) {
                return round(($mark->p1_avg + $mark->p2_avg) / 2, 2);
            }
        } else {
            // Moyenne semestre 2 = (P3 + P4) / 2
            if ($mark->p3_avg !== null && $mark->p4_avg !== null) {
                return round(($mark->p3_avg + $mark->p4_avg) / 2, 2);
            }
        }

        return null;
    }
}
