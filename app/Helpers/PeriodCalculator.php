<?php

namespace App\Helpers;

use App\Models\Assignment\AssignmentSubmission;
use App\Models\Mark;
use App\Models\SubjectGradeConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeriodCalculator
{
    /**
     * Calculer la note de période pour un étudiant dans une matière
     * 
     * Calcule la SOMME des notes de devoirs et interrogations pour la période,
     * puis ramène le total sur la cote max configurée pour la matière.
     * 
     * @param int $student_id
     * @param int $subject_id
     * @param int $period (1, 2, 3, ou 4)
     * @param int $class_id
     * @param string $year
     * @return array|null ['total' => X, 'max' => Y, 'details' => [...]]
     */
    public static function calculatePeriodScore($student_id, $subject_id, $period, $class_id, $year)
    {
        // Récupérer toutes les soumissions notées pour cette période (devoirs + interrogations)
        $submissions = AssignmentSubmission::whereHas('assignment', function($query) use ($subject_id, $period, $class_id) {
            $query->where('subject_id', $subject_id)
                  ->where('period', $period)
                  ->where('my_class_id', $class_id)
                  ->whereIn('status', ['active', 'closed']);
        })
        ->where('student_id', $student_id)
        ->where('status', 'graded')
        ->whereNotNull('score')
        ->with('assignment')
        ->get();

        if ($submissions->isEmpty()) {
            return null;
        }

        // Calculer la somme des notes et le total des max_scores
        $totalScore = 0;
        $totalMaxScore = 0;
        $details = [
            'devoirs' => [],
            'interrogations' => []
        ];

        foreach ($submissions as $submission) {
            $assignment = $submission->assignment;
            $totalScore += $submission->score;
            $totalMaxScore += $assignment->max_score;
            
            // Catégoriser par type
            $type = $assignment->type ?? 'devoir';
            $key = $type === 'interrogation' ? 'interrogations' : 'devoirs';
            $details[$key][] = [
                'title' => $assignment->title,
                'score' => $submission->score,
                'max' => $assignment->max_score
            ];
        }

        // Récupérer la cote max configurée pour cette matière/classe
        $config = SubjectGradeConfig::getConfig($class_id, $subject_id, $year);
        $periodMaxScore = $config ? $config->period_max_score : 20; // Par défaut 20

        // Calculer la note ramenée sur la cote max de période
        // Exemple: Si total = 15/25 et cote période = 20, alors note = (15/25) * 20 = 12
        $normalizedScore = $totalMaxScore > 0 
            ? round(($totalScore / $totalMaxScore) * $periodMaxScore, 2) 
            : 0;

        return [
            'raw_total' => $totalScore,
            'raw_max' => $totalMaxScore,
            'normalized_score' => $normalizedScore,
            'period_max' => $periodMaxScore,
            'percentage' => $totalMaxScore > 0 ? round(($totalScore / $totalMaxScore) * 100, 2) : 0,
            'details' => $details
        ];
    }

    /**
     * Mettre à jour la note de période dans la table marks
     * 
     * Met à jour DEUX colonnes :
     * - p{X}_avg : Moyenne calculée automatiquement
     * - t{X} : Colonne utilisée par le bulletin et la tabulation
     * 
     * @param int $student_id
     * @param int $subject_id
     * @param int $period
     * @param int $class_id
     * @param int $section_id
     * @param string $year
     * @return array|null Le résultat du calcul
     */
    public static function updatePeriodAverageInMarks($student_id, $subject_id, $period, $class_id, $section_id, $year)
    {
        $result = self::calculatePeriodScore($student_id, $subject_id, $period, $class_id, $year);

        if ($result === null) {
            return null; // Pas de notes, on ne fait rien
        }

        // Colonnes à mettre à jour
        $pColumn = 'p' . $period . '_avg';  // p1_avg, p2_avg, etc.
        $tColumn = 't' . $period;            // t1, t2, etc.

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
            ]
        );

        // Mettre à jour les deux colonnes (synchronisation)
        $mark->update([
            $pColumn => $result['normalized_score'],
            $tColumn => $result['normalized_score']
        ]);

        Log::info("PeriodCalculator: Student $student_id, Subject $subject_id, Period $period = {$result['normalized_score']}/{$result['period_max']} (raw: {$result['raw_total']}/{$result['raw_max']})");

        return $result;
    }

    /**
     * Récupérer ou créer l'examen pour une période donnée
     */
    protected static function getOrCreateExamForPeriod($period, $year)
    {
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
     */
    public static function updateAllPeriodAveragesForStudent($student_id, $class_id, $section_id, $year)
    {
        $subjects = DB::table('assignments')
            ->where('my_class_id', $class_id)
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
     * Recalculer toutes les notes de période pour une classe et matière
     * Utile après modification de structure
     */
    public static function recalculateForClassSubject($class_id, $subject_id, $period, $year)
    {
        $students = \App\Models\StudentRecord::where('my_class_id', $class_id)
            ->where('session', $year)
            ->get();

        $results = [];
        foreach ($students as $student) {
            $result = self::updatePeriodAverageInMarks(
                $student->user_id,
                $subject_id,
                $period,
                $class_id,
                $student->section_id,
                $year
            );
            if ($result) {
                $results[$student->user_id] = $result;
            }
        }

        return $results;
    }

    /**
     * Calculer la moyenne du semestre à partir des moyennes de périodes
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
            // Utiliser t1 et t2 pour compatibilité
            $p1 = $mark->t1 ?? $mark->p1_avg;
            $p2 = $mark->t2 ?? $mark->p2_avg;
            if ($p1 !== null && $p2 !== null) {
                return round(($p1 + $p2) / 2, 2);
            }
        } else {
            $p3 = $mark->t3 ?? $mark->p3_avg;
            $p4 = $mark->t4 ?? $mark->p4_avg;
            if ($p3 !== null && $p4 !== null) {
                return round(($p3 + $p4) / 2, 2);
            }
        }

        return null;
    }

    /**
     * Obtenir le détail des notes d'un étudiant pour une période
     */
    public static function getStudentPeriodDetails($student_id, $subject_id, $period, $class_id, $year)
    {
        return self::calculatePeriodScore($student_id, $subject_id, $period, $class_id, $year);
    }
}
