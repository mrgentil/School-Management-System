<?php

namespace App\Services;

use App\Models\Mark;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Helpers\Qs;
use Illuminate\Support\Collection;

/**
 * Service de calcul amélioré pour les proclamations RDC
 * Prend en compte TOUS les types d'évaluations :
 * - Devoirs (assignments)
 * - Interrogations (colonnes t1-t4)
 * - Interrogations générales
 * - Examens (s1_exam, s2_exam)
 */
class ImprovedProclamationCalculationService
{
    /**
     * Pondération par défaut RDC
     */
    const DEFAULT_WEIGHTS = [
        'devoirs' => 0.30,              // 30% pour les devoirs
        'interrogations' => 0.40,       // 40% pour les interrogations
        'interrogation_generale' => 0.30 // 30% pour l'interrogation générale
    ];

    /**
     * Calculer la moyenne d'un étudiant pour une période donnée
     * NOUVELLE VERSION : Prend en compte devoirs + interrogations + interro générale
     */
    public function calculateStudentPeriodAverage($studentId, $classId, $period, $year = null)
    {
        $year = $year ?: Qs::getSetting('current_session');
        
        // Récupérer toutes les matières de la classe
        $subjects = Subject::where('my_class_id', $classId)->get();
        
        $subjectAverages = [];
        $totalPercentage = 0;
        $subjectCount = 0;
        
        foreach ($subjects as $subject) {
            $subjectAverage = $this->calculateSubjectPeriodAverage(
                $studentId, 
                $subject->id, 
                $classId, 
                $period, 
                $year
            );
            
            if ($subjectAverage !== null) {
                $subjectAverages[$subject->id] = [
                    'subject_name' => $subject->name,
                    'percentage' => $subjectAverage['percentage'],
                    'points' => $subjectAverage['points'],
                    'details' => $subjectAverage['details'] // Détails par type d'évaluation
                ];
                
                $totalPercentage += $subjectAverage['percentage'];
                $subjectCount++;
            }
        }
        
        $overallAverage = $subjectCount > 0 ? $totalPercentage / $subjectCount : 0;
        
        return [
            'student_id' => $studentId,
            'period' => $period,
            'overall_percentage' => $overallAverage,
            'overall_points' => ($overallAverage / 100) * 20,
            'subject_averages' => $subjectAverages,
            'subject_count' => $subjectCount
        ];
    }

    /**
     * Calculer la moyenne d'une matière pour une période
     * MÉTHODE COMPLÈTE avec devoirs + interrogations + interro générale
     */
    private function calculateSubjectPeriodAverage($studentId, $subjectId, $classId, $period, $year)
    {
        // Récupérer la configuration des cotes (optionnel - utiliser valeurs par défaut si absent)
        $config = SubjectGradeConfig::getConfig($classId, $subjectId, $year);
        
        // Utiliser les valeurs par défaut si pas de config
        $maxPoints = $config ? ($config->period_max_points ?? 20) : 20;
        
        // 1. RÉCUPÉRER LES NOTES DES DEVOIRS
        $devoirsAverage = $this->calculateDevoirsAverage($studentId, $subjectId, $classId, $period, $year, $maxPoints);
        
        // 2. RÉCUPÉRER LES NOTES DES INTERROGATIONS
        $interrogationsAverage = $this->calculateInterrogationsAverage($studentId, $subjectId, $classId, $period, $year, $maxPoints);
        
        // 3. RÉCUPÉRER LA NOTE DE L'INTERROGATION GÉNÉRALE
        $interroGeneraleAverage = $this->calculateInterrogationGeneraleAverage($studentId, $subjectId, $classId, $period, $year, $maxPoints);
        
        // 4. CALCULER LA MOYENNE
        $details = [];
        $percentages = [];
        
        if ($devoirsAverage !== null) {
            $percentages[] = $devoirsAverage['percentage'];
            $details['devoirs'] = $devoirsAverage;
        }
        
        if ($interrogationsAverage !== null) {
            $percentages[] = $interrogationsAverage['percentage'];
            $details['interrogations'] = $interrogationsAverage;
        }
        
        if ($interroGeneraleAverage !== null) {
            $percentages[] = $interroGeneraleAverage['percentage'];
            $details['interrogation_generale'] = $interroGeneraleAverage;
        }
        
        if (empty($percentages)) {
            return null; // Aucune note disponible
        }
        
        // Moyenne simple de toutes les sources disponibles
        $finalPercentage = array_sum($percentages) / count($percentages);
        
        return [
            'percentage' => $finalPercentage,
            'points' => ($finalPercentage / 100) * $maxPoints,
            'details' => $details
        ];
    }

    /**
     * Calculer la moyenne des DEVOIRS pour une période
     */
    private function calculateDevoirsAverage($studentId, $subjectId, $classId, $period, $year, $maxPoints)
    {
        // Récupérer tous les devoirs de cette période
        $assignments = Assignment::where('my_class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('period', $period)
            ->where('year', $year)
            ->get();
        
        if ($assignments->isEmpty()) {
            return null;
        }
        
        $scores = [];
        $assignmentDetails = [];
        
        foreach ($assignments as $assignment) {
            $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
                ->where('student_id', $studentId)
                ->first();
            
            if ($submission && $submission->score !== null) {
                // Normaliser la note sur la cote RDC
                $assignmentMaxScore = $assignment->max_score ?? $maxPoints;
                $normalizedScore = ($submission->score / $assignmentMaxScore) * $maxPoints;
                $scores[] = $normalizedScore;
                
                $assignmentDetails[] = [
                    'title' => $assignment->title,
                    'score' => $submission->score,
                    'max_score' => $assignmentMaxScore,
                    'normalized' => $normalizedScore
                ];
            }
        }
        
        if (empty($scores)) {
            return null;
        }
        
        $average = array_sum($scores) / count($scores);
        $percentage = ($average / $maxPoints) * 100;
        
        return [
            'average' => $average,
            'percentage' => $percentage,
            'count' => count($scores),
            'assignments' => $assignmentDetails
        ];
    }

    /**
     * Calculer la moyenne des INTERROGATIONS pour une période
     */
    private function calculateInterrogationsAverage($studentId, $subjectId, $classId, $period, $year, $maxPoints)
    {
        // Récupérer la note d'interrogation de la colonne t1-t4
        // Chercher TOUS les marks pour cet étudiant/matière (peu importe exam_id)
        $mark = Mark::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('my_class_id', $classId)
            ->where('year', $year)
            ->first();
        
        // DEBUG
        $periodColumn = 't' . $period;
        \Log::debug("calculateInterrogationsAverage - Student: $studentId, Subject: $subjectId, Class: $classId, Year: $year, Period: $period");
        \Log::debug("Mark found: " . ($mark ? "YES (id: {$mark->id}, t1: {$mark->t1}, t2: {$mark->t2})" : "NO"));
        
        if (!$mark) {
            return null;
        }
        
        $periodColumn = 't' . $period; // t1, t2, t3, t4
        $score = $mark->$periodColumn;
        
        // Accepter les valeurs numériques, y compris 0
        if ($score === null || $score === '' || !is_numeric($score)) {
            return null;
        }
        
        $score = floatval($score);
        
        // Si maxPoints est 0, utiliser 20 par défaut
        $maxPoints = $maxPoints > 0 ? $maxPoints : 20;
        
        // Calculer le pourcentage
        $percentage = ($score / $maxPoints) * 100;
        
        return [
            'average' => $score,
            'percentage' => $percentage,
            'count' => 1,
            'note' => "Interrogations de la période $period"
        ];
    }

    /**
     * Calculer la note de l'INTERROGATION GÉNÉRALE pour une période
     */
    private function calculateInterrogationGeneraleAverage($studentId, $subjectId, $classId, $period, $year, $maxPoints)
    {
        // Pour l'instant, on utilise la colonne TCA comme interrogation générale
        // Vous pouvez créer une table séparée pour les interrogations générales si nécessaire
        $mark = Mark::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('my_class_id', $classId)
            ->where('year', $year)
            ->first();
        
        if (!$mark || $mark->tca === null || $mark->tca === '') {
            return null;
        }
        
        $score = $mark->tca;
        $percentage = ($score / $maxPoints) * 100;
        
        return [
            'average' => $score,
            'percentage' => $percentage,
            'count' => 1,
            'note' => "Interrogation générale"
        ];
    }

    /**
     * Calculer la moyenne d'un étudiant pour un semestre
     */
    public function calculateStudentSemesterAverage($studentId, $classId, $semester, $year = null)
    {
        $year = $year ?: Qs::getSetting('current_session');
        
        // Déterminer les périodes du semestre
        $periods = $semester == 1 ? [1, 2] : [3, 4];
        
        // Récupérer toutes les matières de la classe
        $subjects = Subject::where('my_class_id', $classId)->get();
        
        $subjectAverages = [];
        $totalPercentage = 0;
        $subjectCount = 0;
        
        foreach ($subjects as $subject) {
            // Calculer la moyenne des périodes
            $periodAverages = [];
            foreach ($periods as $period) {
                $periodAvg = $this->calculateSubjectPeriodAverage(
                    $studentId, 
                    $subject->id, 
                    $classId, 
                    $period, 
                    $year
                );
                if ($periodAvg !== null) {
                    $periodAverages[] = $periodAvg['percentage'];
                }
            }
            
            // Calculer la moyenne d'examen
            $examAverage = $this->calculateSubjectExamAverage(
                $studentId, 
                $subject->id, 
                $classId, 
                $semester, 
                $year
            );
            
            // Calculer la moyenne semestrielle
            if (!empty($periodAverages) || $examAverage !== null) {
                $periodMean = !empty($periodAverages) ? array_sum($periodAverages) / count($periodAverages) : 0;
                $examMean = $examAverage !== null ? $examAverage['percentage'] : 0;
                
                // Moyenne pondérée : 40% périodes + 60% examen
                $semesterAverage = ($periodMean * 0.4) + ($examMean * 0.6);
                
                $subjectAverages[$subject->id] = [
                    'subject_name' => $subject->name,
                    'period_average' => $periodMean,
                    'exam_average' => $examMean,
                    'semester_percentage' => $semesterAverage,
                    'semester_points' => ($semesterAverage / 100) * 20
                ];
                
                $totalPercentage += $semesterAverage;
                $subjectCount++;
            }
        }
        
        $overallAverage = $subjectCount > 0 ? $totalPercentage / $subjectCount : 0;
        
        return [
            'student_id' => $studentId,
            'semester' => $semester,
            'overall_percentage' => $overallAverage,
            'overall_points' => ($overallAverage / 100) * 20,
            'subject_averages' => $subjectAverages,
            'subject_count' => $subjectCount
        ];
    }

    /**
     * Calculer la moyenne d'examen pour un semestre
     */
    private function calculateSubjectExamAverage($studentId, $subjectId, $classId, $semester, $year)
    {
        $config = SubjectGradeConfig::getConfig($classId, $subjectId, $year);
        
        if (!$config) {
            return null;
        }

        $maxPoints = $config->exam_max_score ?? 40;
        
        $mark = Mark::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('my_class_id', $classId)
            ->where('year', $year)
            ->first();
        
        if (!$mark) {
            return null;
        }
        
        // Utiliser la colonne d'examen appropriée
        $examColumn = 's' . $semester . '_exam';
        $examScore = $mark->$examColumn;
        
        if ($examScore === null || $examScore === '') {
            return null;
        }
        
        $percentage = ($examScore / $maxPoints) * 100;
        
        return [
            'average' => $examScore,
            'percentage' => $percentage,
            'max_points' => $maxPoints
        ];
    }

    /**
     * Calculer le classement d'une classe pour une période
     */
    public function calculateClassRankingForPeriod($classId, $period, $year = null)
    {
        $year = $year ?: Qs::getSetting('current_session');
        
        // Récupérer tous les étudiants de la classe
        // Note: student_records utilise 'session' et non 'year'
        $students = \App\Models\StudentRecord::where('my_class_id', $classId)
            ->where('session', $year)
            ->with('user')
            ->get();
        
        $rankings = [];
        
        foreach ($students as $student) {
            $average = $this->calculateStudentPeriodAverage(
                $student->user_id, 
                $classId, 
                $period, 
                $year
            );
            
            if ($average && $average['overall_percentage'] > 0) {
                $rankings[] = [
                    'student_id' => $student->user_id,
                    'student_name' => $student->user->name,
                    'admission_no' => $student->adm_no,
                    'average_percentage' => $average['overall_percentage'],
                    'average_points' => $average['overall_points'],
                    'subject_count' => $average['subject_count']
                ];
            }
        }
        
        // Trier par moyenne décroissante
        usort($rankings, function($a, $b) {
            return $b['average_percentage'] <=> $a['average_percentage'];
        });
        
        // Ajouter les rangs et les mentions
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
            
            // Ajouter mention basée sur le pourcentage
            $percentage = $ranking['average_percentage'];
            if ($percentage >= 80) {
                $ranking['mention'] = 'Très Bien';
            } elseif ($percentage >= 70) {
                $ranking['mention'] = 'Bien';
            } elseif ($percentage >= 60) {
                $ranking['mention'] = 'Assez Bien';
            } elseif ($percentage >= 50) {
                $ranking['mention'] = 'Passable';
            } else {
                $ranking['mention'] = 'Insuffisant';
            }
            
            // Renommer les clés pour correspondre à la vue
            $ranking['percentage'] = $ranking['average_percentage'];
            $ranking['points'] = $ranking['average_points'];
        }
        
        return [
            'total_students' => count($rankings),
            'rankings' => $rankings
        ];
    }

    /**
     * Calculer le classement d'une classe pour un semestre
     */
    public function calculateClassRankingForSemester($classId, $semester, $year = null)
    {
        $year = $year ?: Qs::getSetting('current_session');
        
        // Récupérer tous les étudiants de la classe
        // Note: student_records utilise 'session' et non 'year'
        $students = \App\Models\StudentRecord::where('my_class_id', $classId)
            ->where('session', $year)
            ->with('user')
            ->get();
        
        $rankings = [];
        
        foreach ($students as $student) {
            $average = $this->calculateStudentSemesterAverage(
                $student->user_id, 
                $classId, 
                $semester, 
                $year
            );
            
            if ($average && $average['overall_percentage'] > 0) {
                $rankings[] = [
                    'student_id' => $student->user_id,
                    'student_name' => $student->user->name,
                    'admission_no' => $student->adm_no,
                    'average_percentage' => $average['overall_percentage'],
                    'average_points' => $average['overall_points'],
                    'subject_count' => $average['subject_count']
                ];
            }
        }
        
        // Trier par moyenne décroissante
        usort($rankings, function($a, $b) {
            return $b['average_percentage'] <=> $a['average_percentage'];
        });
        
        // Ajouter les rangs et les mentions
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
            
            // Ajouter mention basée sur le pourcentage
            $percentage = $ranking['average_percentage'];
            if ($percentage >= 80) {
                $ranking['mention'] = 'Très Bien';
            } elseif ($percentage >= 70) {
                $ranking['mention'] = 'Bien';
            } elseif ($percentage >= 60) {
                $ranking['mention'] = 'Assez Bien';
            } elseif ($percentage >= 50) {
                $ranking['mention'] = 'Passable';
            } else {
                $ranking['mention'] = 'Insuffisant';
            }
            
            // Renommer les clés pour correspondre à la vue
            $ranking['percentage'] = $ranking['average_percentage'];
            $ranking['points'] = $ranking['average_points'];
        }
        
        return [
            'total_students' => count($rankings),
            'rankings' => $rankings
        ];
    }
}
