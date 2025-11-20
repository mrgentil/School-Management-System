<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Models\Assignment\AssignmentSubmission;
use App\Models\Subject;
use App\Models\Mark;
use App\Helpers\Qs;
use App\Helpers\PeriodCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyGradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
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
     * Afficher le bulletin complet (toutes les périodes)
     */
    public function bulletin()
    {
        $student = Auth::user();
        $studentRecord = $student->student_record()->with(['my_class.academicSection', 'my_class.option', 'section'])->first();
        
        if (!$studentRecord) {
            return back()->with('flash_danger', 'Profil étudiant non trouvé.');
        }

        $year = Qs::getCurrentSession();

        // Récupérer toutes les matières
        $subjectIds = Assignment::where('my_class_id', $studentRecord->my_class_id)
                                ->where('section_id', $studentRecord->section_id)
                                ->where('status', 'active')
                                ->distinct()
                                ->pluck('subject_id');
        
        $subjects = Subject::whereIn('id', $subjectIds)->orderBy('name')->get();

        // Pour chaque matière, récupérer les moyennes de toutes les périodes
        $bulletinData = [];

        foreach ($subjects as $subject) {
            $mark = Mark::where([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'my_class_id' => $studentRecord->my_class_id,
                'year' => $year,
            ])->first();

            $bulletinData[] = [
                'subject' => $subject,
                'p1_avg' => $mark ? $mark->p1_avg : null,
                'p2_avg' => $mark ? $mark->p2_avg : null,
                'p3_avg' => $mark ? $mark->p3_avg : null,
                'p4_avg' => $mark ? $mark->p4_avg : null,
                's1_avg' => $mark && $mark->p1_avg && $mark->p2_avg ? 
                    round(($mark->p1_avg + $mark->p2_avg) / 2, 2) : null,
                's2_avg' => $mark && $mark->p3_avg && $mark->p4_avg ? 
                    round(($mark->p3_avg + $mark->p4_avg) / 2, 2) : null,
                's1_exam' => $mark ? $mark->s1_exam : null,
                's2_exam' => $mark ? $mark->s2_exam : null,
            ];
        }

        $data = [
            'bulletinData' => $bulletinData,
            'student' => $student,
            'studentRecord' => $studentRecord,
            'year' => $year,
        ];

        return view('pages.student.grades.bulletin', $data);
    }
}
