<?php

namespace App\Services;

use App\Models\Mark;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Models\StudentRecord;
use App\Helpers\Qs;
use Illuminate\Support\Collection;

class ProclamationCalculationService
{
    /**
     * Types d'évaluations RDC
     */
    const EVALUATION_TYPES = [
        'devoir' => 'Devoir',
        'interrogation' => 'Interrogation',
        'interrogation_generale' => 'Interrogation Générale',
        'examen' => 'Examen'
    ];

    /**
     * Calculer les moyennes d'un étudiant pour une période donnée
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
            $subjectAverage = $this->calculateSubjectAverage(
                $studentId, 
                $subject->id, 
                $classId, 
                $period, 
                'period',
                $year
            );
            
            if ($subjectAverage !== null) {
                $subjectAverages[$subject->id] = [
                    'subject_name' => $subject->name,
                    'percentage' => $subjectAverage,
                    'points' => ($subjectAverage / 100) * 20 // Conversion en points sur 20
                ];
                
                $totalPercentage += $subjectAverage;
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
                $periodAvg = $this->calculateSubjectAverage(
                    $studentId, 
                    $subject->id, 
                    $classId, 
                    $period, 
                    'period',
                    $year
                );
                if ($periodAvg !== null) {
                    $periodAverages[] = $periodAvg;
                }
            }
            
            // Calculer la moyenne d'examen
            $examAverage = $this->calculateSubjectAverage(
                $studentId, 
                $subject->id, 
                $classId, 
                $semester, 
                'exam',
                $year
            );
            
            // Calculer la moyenne semestrielle
            if (!empty($periodAverages) || $examAverage !== null) {
                $periodMean = !empty($periodAverages) ? array_sum($periodAverages) / count($periodAverages) : 0;
                $examMean = $examAverage ?: 0;
                
                // Moyenne pondérée : 40% périodes + 60% examen (configurable)
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
     * Calculer la moyenne d'une matière pour un étudiant
     */
    private function calculateSubjectAverage($studentId, $subjectId, $classId, $periodOrSemester, $type, $year)
    {
        // Récupérer la configuration des cotes pour cette matière
        $config = SubjectGradeConfig::getConfig($classId, $subjectId, $year);
        
        if (!$config) {
            return null; // Pas de configuration trouvée
        }
        
        // Déterminer la cote maximale selon le type
        $maxPoints = $type === 'exam' ? $config->exam_max_points : $config->period_max_points;
        
        if ($type === 'exam') {
            // Pour les examens, utiliser la colonne 'exm' ou 's1_exam'/'s2_exam'
            $mark = Mark::where('student_id', $studentId)
                       ->where('subject_id', $subjectId)
                       ->where('my_class_id', $classId)
                       ->where('year', $year)
                       ->whereHas('exam', function($query) use ($periodOrSemester) {
                           $query->where('semester', $periodOrSemester);
                       })
                       ->first();
            
            if (!$mark) {
                return null;
            }
            
            // Utiliser la colonne d'examen appropriée
            $examScore = $periodOrSemester == 1 ? $mark->s1_exam : $mark->s2_exam;
            $examScore = $examScore ?: $mark->exm; // Fallback sur exm si s1_exam/s2_exam vide
            
            if ($examScore === null || $examScore === '') {
                return null;
            }
            
            // Utiliser la cote spécifique ou la cote par défaut
            $actualMaxPoints = $mark->max_points ?: $maxPoints;
            
            return ($examScore / $actualMaxPoints) * 100;
            
        } else {
            // Pour les périodes, utiliser les colonnes t1, t2, t3, t4 et autres évaluations
            $mark = Mark::where('student_id', $studentId)
                       ->where('subject_id', $subjectId)
                       ->where('my_class_id', $classId)
                       ->where('year', $year)
                       ->whereHas('exam', function($query) use ($periodOrSemester) {
                           $query->where('period', $periodOrSemester);
                       })
                       ->first();
            
            if (!$mark) {
                return null;
            }
            
            // Récupérer les notes de la période
            $periodScores = [];
            
            // Notes principales (devoirs, interrogations)
            $periodColumn = 't' . $periodOrSemester; // t1, t2, t3, t4
            if (isset($mark->$periodColumn) && $mark->$periodColumn !== null && $mark->$periodColumn !== '') {
                $periodScores['test'] = $mark->$periodColumn;
            }
            
            // TCA (Travaux Continus d'Apprentissage)
            if ($mark->tca !== null && $mark->tca !== '') {
                $periodScores['tca'] = $mark->tca;
            }
            
            // TEX (Travaux d'Expression) selon la période
            $texColumns = ['tex1', 'tex2', 'tex3'];
            foreach ($texColumns as $texCol) {
                if (isset($mark->$texCol) && $mark->$texCol !== null && $mark->$texCol !== '') {
                    $periodScores[$texCol] = $mark->$texCol;
                }
            }
            
            if (empty($periodScores)) {
                return null;
            }
            
            // Calculer la moyenne pondérée des évaluations de la période
            $totalWeightedScore = 0;
            $totalWeight = 0;
            
            // Pondération par type d'évaluation
            $weights = [
                'test' => 0.5,    // 50% pour les tests principaux
                'tca' => 0.3,     // 30% pour les TCA
                'tex1' => 0.1,    // 10% pour TEX1
                'tex2' => 0.05,   // 5% pour TEX2
                'tex3' => 0.05    // 5% pour TEX3
            ];
            
            foreach ($periodScores as $type => $score) {
                $weight = $weights[$type] ?? 0.1; // Poids par défaut
                $actualMaxPoints = $mark->max_points ?: $maxPoints;
                
                $percentage = ($score / $actualMaxPoints) * 100;
                $totalWeightedScore += $percentage * $weight;
                $totalWeight += $weight;
            }
            
            return $totalWeight > 0 ? $totalWeightedScore / $totalWeight : 0;
        }
    }

    /**
     * Calculer la moyenne pondérée selon les types d'évaluations
     */
    private function calculateWeightedAverage($evaluationAverages, $type)
    {
        // Pondération par défaut (configurable)
        $weights = [
            'devoir' => 0.3,                    // 30%
            'interrogation' => 0.4,             // 40%
            'interrogation_generale' => 0.2,    // 20%
            'examen' => 1.0                     // 100% pour les examens
        ];
        
        if ($type === 'exam') {
            // Pour les examens, seul le type 'examen' compte
            return $evaluationAverages['examen'] ?? 0;
        }
        
        // Pour les périodes, calculer la moyenne pondérée
        $totalWeightedScore = 0;
        $totalWeight = 0;
        
        foreach ($evaluationAverages as $evalType => $average) {
            if ($evalType !== 'examen' && isset($weights[$evalType])) {
                $weight = $weights[$evalType];
                $totalWeightedScore += $average * $weight;
                $totalWeight += $weight;
            }
        }
        
        return $totalWeight > 0 ? $totalWeightedScore / $totalWeight : 0;
    }

    /**
     * Calculer le classement d'une classe pour une période
     */
    public function calculateClassRankingForPeriod($classId, $period, $year = null)
    {
        $year = $year ?: Qs::getSetting('current_session');
        
        // Récupérer tous les étudiants de la classe
        $students = StudentRecord::where('my_class_id', $classId)
                                ->where('year', $year)
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
            
            if ($average['overall_percentage'] > 0) {
                $rankings[] = [
                    'student_id' => $student->user_id,
                    'student_name' => $student->user->name,
                    'student_record_id' => $student->id,
                    'percentage' => $average['overall_percentage'],
                    'points' => $average['overall_points'],
                    'subject_averages' => $average['subject_averages'],
                    'subject_count' => $average['subject_count']
                ];
            }
        }
        
        // Trier par pourcentage décroissant
        usort($rankings, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });
        
        // Ajouter les rangs
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
            $ranking['mention'] = $this->getMention($ranking['percentage']);
        }
        
        return [
            'class_id' => $classId,
            'period' => $period,
            'year' => $year,
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
        $students = StudentRecord::where('my_class_id', $classId)
                                ->where('year', $year)
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
            
            if ($average['overall_percentage'] > 0) {
                $rankings[] = [
                    'student_id' => $student->user_id,
                    'student_name' => $student->user->name,
                    'student_record_id' => $student->id,
                    'percentage' => $average['overall_percentage'],
                    'points' => $average['overall_points'],
                    'subject_averages' => $average['subject_averages'],
                    'subject_count' => $average['subject_count']
                ];
            }
        }
        
        // Trier par pourcentage décroissant
        usort($rankings, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });
        
        // Ajouter les rangs
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
            $ranking['mention'] = $this->getMention($ranking['percentage']);
        }
        
        return [
            'class_id' => $classId,
            'semester' => $semester,
            'year' => $year,
            'total_students' => count($rankings),
            'rankings' => $rankings
        ];
    }

    /**
     * Obtenir la mention selon le pourcentage
     */
    private function getMention($percentage)
    {
        if ($percentage >= 80) return 'Très Bien';
        if ($percentage >= 70) return 'Bien';
        if ($percentage >= 60) return 'Assez Bien';
        if ($percentage >= 50) return 'Passable';
        return 'Insuffisant';
    }

    /**
     * Mettre à jour automatiquement les moyennes après saisie d'une note
     */
    public function updateAveragesAfterMarkEntry($markId)
    {
        $mark = Mark::find($markId);
        
        if (!$mark) {
            return false;
        }
        
        // Recalculer les moyennes pour cet étudiant
        $exam = $mark->exam;
        
        if ($exam->period) {
            // Recalculer la moyenne de période
            $this->calculateStudentPeriodAverage(
                $mark->student_id,
                $mark->my_class_id,
                $exam->period,
                $mark->year
            );
        }
        
        if ($exam->semester) {
            // Recalculer la moyenne de semestre
            $this->calculateStudentSemesterAverage(
                $mark->student_id,
                $mark->my_class_id,
                $exam->semester,
                $mark->year
            );
        }
        
        return true;
    }
}
