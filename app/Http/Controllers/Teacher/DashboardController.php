<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Models\User;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\StudentRecord;
use App\Models\TimeTable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $teacher = Auth::user();
        $year = Qs::getCurrentSession();

        // Classes où l'enseignant est titulaire
        $titularClasses = MyClass::where('teacher_id', $teacher->id)
            ->with(['class_type'])
            ->get();

        // Matières enseignées par ce prof
        $teacherSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $subjectIds = $teacherSubjects->pluck('id');

        // Classes où il enseigne (via les matières)
        $teachingClassIds = Mark::whereIn('subject_id', $subjectIds)
            ->where('year', $year)
            ->distinct()
            ->pluck('my_class_id');

        $teachingClasses = MyClass::whereIn('id', $teachingClassIds)
            ->orWhere('teacher_id', $teacher->id)
            ->with(['class_type'])
            ->get();

        // Nombre d'élèves
        $studentCount = StudentRecord::whereIn('my_class_id', $teachingClasses->pluck('id'))
            ->where('session', $year)
            ->count();

        // Devoirs en attente de notation
        $pendingAssignments = Assignment::where('teacher_id', $teacher->id)
            ->where('due_date', '<', now())
            ->whereDoesntHave('submissions', function($q) {
                $q->whereNotNull('grade');
            })
            ->count();

        // Cours du jour
        $todayCourses = TimeTable::where('teacher_id', $teacher->id)
            ->where('day', strtolower(Carbon::now()->format('l')))
            ->with(['my_class', 'subject'])
            ->orderBy('time_start')
            ->get();

        // Cours de la semaine
        $weekCourses = TimeTable::where('teacher_id', $teacher->id)
            ->with(['my_class', 'subject'])
            ->orderByRaw("FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderBy('time_start')
            ->get()
            ->groupBy('day');

        // Statistiques par classe (moyennes)
        $classStats = $this->getClassStats($teachingClasses, $subjectIds, $year);

        // Élèves en difficulté (moyenne < 10)
        $strugglingStudents = $this->getStrugglingStudents($teachingClasses, $subjectIds, $year);

        // Statistiques générales
        $stats = [
            'total_classes' => $teachingClasses->count(),
            'total_students' => $studentCount,
            'total_subjects' => $teacherSubjects->count(),
            'titular_classes' => $titularClasses->count(),
            'pending_grades' => $pendingAssignments,
            'today_courses' => $todayCourses->count(),
        ];

        // Dernières notes saisies
        $recentMarks = Mark::whereIn('subject_id', $subjectIds)
            ->where('year', $year)
            ->with(['student', 'subject', 'my_class'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.teacher.dashboard', compact(
            'teacher', 'titularClasses', 'teachingClasses', 'teacherSubjects',
            'todayCourses', 'weekCourses', 'classStats', 'strugglingStudents',
            'stats', 'recentMarks', 'year'
        ));
    }

    /**
     * Statistiques par classe
     */
    protected function getClassStats($classes, $subjectIds, $year)
    {
        $stats = [];

        foreach ($classes as $class) {
            $marks = Mark::where('my_class_id', $class->id)
                ->whereIn('subject_id', $subjectIds)
                ->where('year', $year)
                ->get();

            if ($marks->isEmpty()) continue;

            $averages = [];
            foreach ($marks as $mark) {
                $avg = $mark->p1_avg ?? $mark->t1 ?? $mark->p2_avg ?? $mark->t2 ?? null;
                if ($avg !== null) {
                    $averages[] = $avg;
                }
            }

            if (count($averages) > 0) {
                $stats[] = [
                    'class' => $class->full_name ?: $class->name,
                    'average' => round(array_sum($averages) / count($averages), 1),
                    'students' => $marks->unique('student_id')->count(),
                ];
            }
        }

        return $stats;
    }

    /**
     * Élèves en difficulté
     */
    protected function getStrugglingStudents($classes, $subjectIds, $year)
    {
        $struggling = [];

        $marks = Mark::whereIn('my_class_id', $classes->pluck('id'))
            ->whereIn('subject_id', $subjectIds)
            ->where('year', $year)
            ->with(['student', 'my_class', 'subject'])
            ->get();

        $byStudent = $marks->groupBy('student_id');

        foreach ($byStudent as $studentId => $studentMarks) {
            $averages = [];
            foreach ($studentMarks as $mark) {
                $avg = $mark->p1_avg ?? $mark->t1 ?? $mark->p2_avg ?? $mark->t2 ?? null;
                if ($avg !== null) {
                    $averages[] = $avg;
                }
            }

            if (count($averages) > 0) {
                $globalAvg = array_sum($averages) / count($averages);
                if ($globalAvg < 10) {
                    $first = $studentMarks->first();
                    $struggling[] = [
                        'student' => $first->student,
                        'class' => $first->my_class,
                        'average' => round($globalAvg, 1),
                    ];
                }
            }
        }

        // Trier par moyenne croissante et limiter à 10
        usort($struggling, fn($a, $b) => $a['average'] <=> $b['average']);
        return array_slice($struggling, 0, 10);
    }
}
