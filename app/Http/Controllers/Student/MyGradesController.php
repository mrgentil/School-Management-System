<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Models\Assignment\AssignmentSubmission;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\SubjectGradeConfig;
use App\Models\BulletinPublication;
use App\Helpers\Qs;
use App\Helpers\PeriodCalculator;
use App\Services\ImprovedProclamationCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyGradesController extends Controller
{
    protected $proclamationService;

    public function __construct(ImprovedProclamationCalculationService $proclamationService)
    {
        $this->middleware('student');
        $this->proclamationService = $proclamationService;
    }

    /**
     * Afficher les notes détaillées par période
     */
    public function index(Request $request)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record()->with(['my_class.academicSection', 'my_class.option', 'section'])->first();
        
        if (!$studentRecord) {
            return back()->with('flash_danger', 'Profil étudiant non trouvé.');
        }

        $year = Qs::getCurrentSession();
        $selectedPeriod = $request->get('period', 1); // Période par défaut: 1

        // Récupérer toutes les matières de l'étudiant
        $subjectIds = Assignment::where('my_class_id', $studentRecord->my_class_id)
                                ->where('section_id', $studentRecord->section_id)
                                ->where('status', 'active')
                                ->distinct()
                                ->pluck('subject_id');
        
        $subjects = Subject::whereIn('id', $subjectIds)->orderBy('name')->get();

        // Pour chaque matière, récupérer les devoirs et notes de la période sélectionnée
        $gradesData = [];
        
        foreach ($subjects as $subject) {
            // Récupérer tous les devoirs de cette période et matière
            $assignments = Assignment::where('my_class_id', $studentRecord->my_class_id)
                                    ->where('section_id', $studentRecord->section_id)
                                    ->where('subject_id', $subject->id)
                                    ->where('period', $selectedPeriod)
                                    ->where('status', 'active')
                                    ->orderBy('created_at')
                                    ->get();

            if ($assignments->isEmpty()) {
                continue;
            }

            // Pour chaque devoir, récupérer la soumission de l'étudiant
            $submissions = [];
            $totalScore = 0;
            $totalMaxScore = 0;
            $gradedCount = 0;

            foreach ($assignments as $assignment) {
                $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)
                    ->where('status', 'graded')
                    ->whereNotNull('score')
                    ->first();

                $submissionData = [
                    'assignment' => $assignment,
                    'submission' => $submission,
                    'score' => $submission ? $submission->score : null,
                    'max_score' => $assignment->max_score,
                    'percentage' => $submission ? round(($submission->score / $assignment->max_score) * 100, 2) : null,
                    'on_twenty' => $submission ? round(($submission->score / $assignment->max_score) * 20, 2) : null,
                ];

                $submissions[] = $submissionData;

                if ($submission) {
                    $totalScore += $submission->score;
                    $totalMaxScore += $assignment->max_score;
                    $gradedCount++;
                }
            }

            // Calculer la moyenne de période depuis la table marks
            $mark = Mark::where([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'my_class_id' => $studentRecord->my_class_id,
                'year' => $year,
            ])->first();

            $periodAverage = null;
            if ($mark) {
                $columnName = 'p' . $selectedPeriod . '_avg';
                $periodAverage = $mark->$columnName;
            }

            $gradesData[] = [
                'subject' => $subject,
                'submissions' => $submissions,
                'total_score' => $totalScore,
                'total_max_score' => $totalMaxScore,
                'total_percentage' => $totalMaxScore > 0 ? round(($totalScore / $totalMaxScore) * 100, 2) : 0,
                'total_on_twenty' => $totalMaxScore > 0 ? round(($totalScore / $totalMaxScore) * 20, 2) : 0,
                'graded_count' => $gradedCount,
                'total_count' => $assignments->count(),
                'period_average' => $periodAverage,
            ];
        }

        $data = [
            'gradesData' => $gradesData,
            'selectedPeriod' => $selectedPeriod,
            'subjects' => $subjects,
            'year' => $year,
        ];

        return view('pages.student.grades.index', $data);
    }

    /**
     * Afficher le bulletin complet - UTILISE LE MÊME SERVICE QUE L'ADMIN
     * Supporte: Période (1-4) ou Semestre (1-2)
     * VÉRIFIE SI LE BULLETIN EST PUBLIÉ
     */
    public function bulletin(Request $request)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record()->with(['my_class.academicSection', 'my_class.option', 'section'])->first();
        
        if (!$studentRecord) {
            return back()->with('flash_danger', 'Profil étudiant non trouvé.');
        }

        $year = Qs::getCurrentSession();
        $type = $request->get('type', 'period'); // 'period' ou 'semester'
        $period = $request->get('period', 1);
        $semester = $request->get('semester', 1);

        // Vérifier si le bulletin est publié
        $periodOrSemester = $type === 'period' ? $period : $semester;
        $isPublished = BulletinPublication::isPublished(
            $studentRecord->my_class_id,
            $type,
            $periodOrSemester,
            $year
        );

        // Récupérer la liste des bulletins publiés pour afficher les options disponibles
        $publishedBulletins = $this->getPublishedBulletins($studentRecord->my_class_id, $year);

        // Si le bulletin demandé n'est pas publié, afficher un message
        if (!$isPublished) {
            return view('pages.student.grades.bulletin_not_published', [
                'student' => $student,
                'studentRecord' => $studentRecord,
                'year' => $year,
                'type' => $type,
                'period' => $period,
                'semester' => $semester,
                'publishedBulletins' => $publishedBulletins,
            ]);
        }

        // Utiliser le service de proclamation (même que l'admin)
        if ($type == 'semester') {
            $averageData = $this->proclamationService->calculateStudentSemesterAverage(
                $student->id,
                $studentRecord->my_class_id,
                $semester,
                $year
            );
        } else {
            $averageData = $this->proclamationService->calculateStudentPeriodAverage(
                $student->id,
                $studentRecord->my_class_id,
                $period,
                $year
            );
        }

        // Récupérer les matières de la classe
        $classSubjects = Subject::where('my_class_id', $studentRecord->my_class_id)
            ->orderBy('name')
            ->get();

        $bulletinData = [];
        $totalPercentage = 0;
        $subjectCount = 0;
        $passedCount = 0;
        $failedCount = 0;

        foreach ($classSubjects as $subject) {
            // Configuration des cotes
            $gradeConfig = SubjectGradeConfig::getConfig(
                $studentRecord->my_class_id, 
                $subject->id, 
                $year
            );
            
            $periodMaxPoints = $gradeConfig ? $gradeConfig->period_max_points : 20;
            $examMaxPoints = $gradeConfig ? $gradeConfig->exam_max_points : 40;

            $data = [
                'subject' => $subject,
                'points' => null,
                'max' => $type == 'semester' ? ($periodMaxPoints * 2 + $examMaxPoints) : $periodMaxPoints,
                'percentage' => null,
                'grade' => null,
                'remark' => null,
                'period_average' => null,
                'exam_average' => null,
            ];

            // Récupérer la moyenne calculée par le service de proclamation
            if ($averageData && isset($averageData['subject_averages'][$subject->id])) {
                $subjectAverage = $averageData['subject_averages'][$subject->id];
                $data['points'] = round($subjectAverage['points'] ?? 0, 2);
                $data['percentage'] = round($subjectAverage['percentage'] ?? $subjectAverage['semester_percentage'] ?? 0, 2);
                $data['grade'] = $this->getGradeLetter($data['percentage']);
                $data['remark'] = $this->getSubjectRemark($data['percentage']);

                if ($type == 'semester') {
                    $data['period_average'] = isset($subjectAverage['period_average']) ? round($subjectAverage['period_average'], 2) : null;
                    $data['exam_average'] = isset($subjectAverage['exam_average']) ? round($subjectAverage['exam_average'], 2) : null;
                }

                $totalPercentage += $data['percentage'];
                $subjectCount++;

                if ($data['percentage'] >= 50) {
                    $passedCount++;
                } else {
                    $failedCount++;
                }
            }

            $bulletinData[] = $data;
        }

        // Moyenne générale depuis le service de proclamation
        $overallPercentage = ($averageData && isset($averageData['overall_percentage'])) 
            ? round($averageData['overall_percentage'], 2) 
            : ($subjectCount > 0 ? round($totalPercentage / $subjectCount, 2) : 0);

        // Calculer le rang via le service de proclamation
        if ($type == 'semester') {
            $rankingData = $this->proclamationService->calculateClassRankingForSemester(
                $studentRecord->my_class_id,
                $semester,
                $year
            );
        } else {
            $rankingData = $this->proclamationService->calculateClassRankingForPeriod(
                $studentRecord->my_class_id,
                $period,
                $year
            );
        }

        $rank = null;
        $totalStudents = 0;
        if ($rankingData && isset($rankingData['rankings'])) {
            $totalStudents = count($rankingData['rankings']);
            foreach ($rankingData['rankings'] as $ranking) {
                if ($ranking['student_id'] == $student->id) {
                    $rank = $ranking['rank'];
                    break;
                }
            }
        }

        // Récupérer les infos de l'école
        $school = [
            'name' => Qs::getSetting('system_name'),
            'logo' => Qs::getSetting('logo'),
            'address' => Qs::getSetting('address'),
            'phone' => Qs::getSetting('phone'),
            'email' => Qs::getSetting('system_email'),
        ];

        $data = [
            'bulletinData' => $bulletinData,
            'student' => $student,
            'studentRecord' => $studentRecord,
            'year' => $year,
            'type' => $type,
            'period' => $period,
            'semester' => $semester,
            'school' => $school,
            'rank' => $rank,
            'totalStudents' => $totalStudents,
            'overallPercentage' => $overallPercentage,
            'overallAverage' => round($overallPercentage * 20 / 100, 2),
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
            'appreciation' => $this->getAppreciationFromPercentage($overallPercentage),
        ];

        return view('pages.student.grades.bulletin', $data);
    }

    /**
     * Obtenir la lettre de note basée sur le pourcentage
     */
    private function getGradeLetter($percentage)
    {
        if ($percentage === null) return null;
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 75) return 'B+';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 65) return 'C+';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 55) return 'D+';
        if ($percentage >= 50) return 'D';
        return 'E';
    }

    /**
     * Obtenir l'appréciation de la matière
     */
    private function getSubjectRemark($percentage)
    {
        if ($percentage === null) return null;
        if ($percentage >= 80) return 'Excellent';
        if ($percentage >= 70) return 'Très Bien';
        if ($percentage >= 60) return 'Bien';
        if ($percentage >= 50) return 'Assez Bien';
        if ($percentage >= 40) return 'Passable';
        return 'Insuffisant';
    }

    /**
     * Obtenir l'appréciation générale basée sur le pourcentage
     */
    private function getAppreciationFromPercentage($percentage)
    {
        if ($percentage >= 80) return 'Excellent';
        if ($percentage >= 70) return 'Très Bien';
        if ($percentage >= 60) return 'Bien';
        if ($percentage >= 50) return 'Assez Bien';
        if ($percentage >= 40) return 'Passable';
        return 'Insuffisant';
    }

    /**
     * Compter le nombre total d'étudiants dans la classe
     */
    private function getTotalStudentsInClass($classId)
    {
        return \App\Models\StudentRecord::where('my_class_id', $classId)->count();
    }

    /**
     * Récupérer la liste des bulletins publiés pour une classe
     */
    private function getPublishedBulletins($classId, $year)
    {
        $published = [
            'periods' => [],
            'semesters' => [],
        ];

        // Vérifier chaque période
        for ($p = 1; $p <= 4; $p++) {
            if (BulletinPublication::isPublished($classId, 'period', $p, $year)) {
                $published['periods'][] = $p;
            }
        }

        // Vérifier chaque semestre
        for ($s = 1; $s <= 2; $s++) {
            if (BulletinPublication::isPublished($classId, 'semester', $s, $year)) {
                $published['semesters'][] = $s;
            }
        }

        return $published;
    }
}
