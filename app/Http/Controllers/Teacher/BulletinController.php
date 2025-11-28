<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Models\BulletinPublication;
use App\Models\Setting;
use App\Helpers\Qs;
use App\Services\ImprovedProclamationCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BulletinController extends Controller
{
    protected $proclamationService;
    protected $year;

    public function __construct(ImprovedProclamationCalculationService $proclamationService)
    {
        $this->middleware('teamSAT');
        $this->proclamationService = $proclamationService;
        $this->year = Qs::getCurrentSession();
    }

    /**
     * Liste des classes du professeur pour voir les bulletins
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Récupérer les classes où le professeur enseigne
        $teacherSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $classIds = $teacherSubjects->pluck('my_class_id')->unique();
        
        $classes = \App\Models\MyClass::whereIn('id', $classIds)
            ->with(['academicSection', 'option'])
            ->get();

        return view('pages.teacher.bulletins.index', compact('classes'));
    }

    /**
     * Liste des étudiants d'une classe
     */
    public function students(Request $request)
    {
        $class_id = $request->my_class_id;
        $section_id = $request->section_id;
        $type = $request->type ?? 'period';
        $period = $request->period ?? 1;
        $semester = $request->semester ?? 1;

        $students = StudentRecord::where('my_class_id', $class_id)
            ->when($section_id, fn($q) => $q->where('section_id', $section_id))
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->get()
            ->sortBy('user.name');

        return view('pages.teacher.bulletins.students', compact(
            'students', 'class_id', 'section_id', 'type', 'period', 'semester'
        ));
    }

    /**
     * Prévisualiser le bulletin d'un étudiant
     */
    public function preview($student_id, Request $request)
    {
        $type = $request->type ?? 'period';
        $period = $request->period ?? 1;
        $semester = $request->semester ?? 1;

        $student = StudentRecord::where('user_id', $student_id)
            ->where('session', $this->year)
            ->with(['user.lga', 'user.state', 'my_class.academicSection', 'my_class.option', 'section', 'option'])
            ->first();

        if (!$student) {
            return back()->with('flash_danger', 'Étudiant non trouvé.');
        }

        // Vérifier si le bulletin est publié
        $periodOrSemester = $type === 'period' ? $period : $semester;
        $isPublished = BulletinPublication::isPublished(
            $student->my_class_id,
            $type,
            $periodOrSemester,
            $this->year
        );

        if (!$isPublished) {
            return back()->with('flash_warning', 'Ce bulletin n\'est pas encore publié.');
        }

        // Utiliser le service de proclamation
        if ($type == 'semester') {
            $averageData = $this->proclamationService->calculateStudentSemesterAverage(
                $student_id, $student->my_class_id, $semester, $this->year
            );
        } else {
            $averageData = $this->proclamationService->calculateStudentPeriodAverage(
                $student_id, $student->my_class_id, $period, $this->year
            );
        }

        // Récupérer les matières
        $classSubjects = Subject::where('my_class_id', $student->my_class_id)->orderBy('name')->get();

        $bulletinData = [];
        $totalPercentage = 0;
        $subjectCount = 0;

        foreach ($classSubjects as $subject) {
            $gradeConfig = SubjectGradeConfig::getConfig($student->my_class_id, $subject->id, $this->year);
            $periodMaxPoints = $gradeConfig ? $gradeConfig->period_max_points : 20;
            $examMaxPoints = $gradeConfig ? $gradeConfig->exam_max_points : 40;

            $data = [
                'subject' => $subject->name,
                'period_max' => $periodMaxPoints,
                'exam_max' => $examMaxPoints,
                'total_obtained' => null,
                'percentage' => null,
            ];

            if ($averageData && isset($averageData['subject_averages'][$subject->id])) {
                $subjectAverage = $averageData['subject_averages'][$subject->id];
                $data['total_obtained'] = round($subjectAverage['points'] ?? 0, 2);
                $data['percentage'] = round($subjectAverage['percentage'] ?? 0, 2);
                
                $data['p1_avg'] = $type == 'period' && $period >= 1 ? $data['total_obtained'] : null;
                $data['p2_avg'] = $type == 'period' && $period >= 2 ? ($subjectAverage['p2_avg'] ?? null) : null;
                $data['p3_avg'] = $type == 'period' && $period >= 3 ? ($subjectAverage['p3_avg'] ?? null) : null;
                $data['p4_avg'] = $type == 'period' && $period >= 4 ? ($subjectAverage['p4_avg'] ?? null) : null;
                $data['s1_exam'] = $type == 'semester' ? ($subjectAverage['exam_average'] ?? null) : null;
                $data['s1_total'] = $type == 'semester' ? ($subjectAverage['semester_points'] ?? $data['total_obtained']) : null;

                $totalPercentage += $data['percentage'];
                $subjectCount++;
            }

            $bulletinData[] = $data;
        }

        $overallPercentage = $subjectCount > 0 ? round($totalPercentage / $subjectCount, 2) : 0;

        // Calculer le rang
        if ($type == 'semester') {
            $rankingData = $this->proclamationService->calculateClassRankingForSemester($student->my_class_id, $semester, $this->year);
        } else {
            $rankingData = $this->proclamationService->calculateClassRankingForPeriod($student->my_class_id, $period, $this->year);
        }

        $rank = null;
        $totalStudents = 0;
        if ($rankingData && isset($rankingData['rankings'])) {
            $totalStudents = count($rankingData['rankings']);
            foreach ($rankingData['rankings'] as $ranking) {
                if ($ranking['student_id'] == $student_id) {
                    $rank = $ranking['rank'];
                    break;
                }
            }
        }

        $school = [
            'name' => Setting::where('type', 'system_name')->value('description') ?? 'École',
            'province' => Setting::where('type', 'province')->value('description') ?? 'KINSHASA',
            'city' => Setting::where('type', 'city')->value('description') ?? '',
            'commune' => Setting::where('type', 'commune')->value('description') ?? '',
            'code' => Setting::where('type', 'school_code')->value('description') ?? '',
        ];

        $stats = [
            'average' => $overallPercentage,
            'total_subjects' => $subjectCount,
        ];

        return view('pages.support_team.bulletins.bulletin_rdc', [
            'student' => $student,
            'studentRecord' => $student,
            'bulletinData' => $bulletinData,
            'stats' => $stats,
            'school' => $school,
            'year' => $this->year,
            'type' => $type,
            'period' => $period,
            'semester' => $semester,
            'rank' => $rank,
            'totalStudents' => $totalStudents,
            'generated_at' => now()->format('d/m/Y à H:i'),
        ]);
    }
}
