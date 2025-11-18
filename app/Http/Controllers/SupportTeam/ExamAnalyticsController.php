<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\MarkRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamAnalyticsController extends Controller
{
    protected $exam, $my_class, $student, $mark;

    public function __construct(ExamRepo $exam, MyClassRepo $my_class, StudentRepo $student, MarkRepo $mark)
    {
        $this->middleware('teamSA');
        $this->exam = $exam;
        $this->my_class = $my_class;
        $this->student = $student;
        $this->mark = $mark;
    }

    public function index()
    {
        $year = Qs::getSetting('current_session');
        $d['exams'] = $this->exam->getExam(['year' => $year]);
        $d['my_classes'] = $this->my_class->all();
        
        return view('pages.support_team.exam_analytics.index', $d);
    }

    public function overview($exam_id)
    {
        $exam = $this->exam->find($exam_id);
        $d['exam'] = $exam;
        
        // Récupérer toutes les notes pour cet examen
        $marks = $this->exam->getMark(['exam_id' => $exam_id, 'year' => $exam->year]);
        $records = $this->exam->getRecord(['exam_id' => $exam_id, 'year' => $exam->year]);
        
        // Statistiques globales
        $d['total_students'] = $records->count();
        $d['total_subjects'] = $marks->pluck('subject_id')->unique()->count();
        $d['avg_class_average'] = round($records->avg('ave'), 2);
        
        // Distribution des moyennes
        $d['grade_distribution'] = [
            'A' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'A')->count(),
            'B' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'B')->count(),
            'C' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'C')->count(),
            'D' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'D')->count(),
            'F' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'F')->count(),
        ];
        
        // Top 10 étudiants
        $d['top_students'] = $records->sortByDesc('ave')->take(10);
        
        // Statistiques par classe
        $d['class_stats'] = [];
        foreach ($this->my_class->all() as $class) {
            $class_records = $records->where('my_class_id', $class->id);
            if ($class_records->count() > 0) {
                $d['class_stats'][] = [
                    'class_name' => $class->name,
                    'students' => $class_records->count(),
                    'average' => round($class_records->avg('ave'), 2),
                    'highest' => round($class_records->max('ave'), 2),
                    'lowest' => round($class_records->min('ave'), 2),
                ];
            }
        }
        
        // Statistiques par matière
        $d['subject_stats'] = [];
        $subjects = $this->my_class->getAllSubjects();
        foreach ($subjects as $subject) {
            $subject_marks = $marks->where('subject_id', $subject->id);
            if ($subject_marks->count() > 0) {
                $tex = 'tex'.$exam->semester;
                $d['subject_stats'][] = [
                    'subject_name' => $subject->name,
                    'average' => round($subject_marks->avg($tex), 2),
                    'highest' => round($subject_marks->max($tex), 2),
                    'lowest' => round($subject_marks->min($tex), 2),
                    'students' => $subject_marks->count(),
                ];
            }
        }

        return view('pages.support_team.exam_analytics.overview', $d);
    }

    public function classAnalysis($exam_id, $class_id)
    {
        $exam = $this->exam->find($exam_id);
        $class = $this->my_class->find($class_id);
        
        $d['exam'] = $exam;
        $d['my_class'] = $class;
        
        $marks = $this->exam->getMark(['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $exam->year]);
        $records = $this->exam->getRecord(['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $exam->year]);
        
        $d['records'] = $records->sortBy('pos');
        $d['marks'] = $marks;
        
        // Statistiques
        $d['class_average'] = round($records->avg('ave'), 2);
        $d['highest_score'] = round($records->max('ave'), 2);
        $d['lowest_score'] = round($records->min('ave'), 2);
        $d['pass_rate'] = round(($records->where('ave', '>=', 50)->count() / max($records->count(), 1)) * 100, 1);
        
        return view('pages.support_team.exam_analytics.class_analysis', $d);
    }

    public function studentProgress($student_id)
    {
        $student = $this->student->find($student_id);
        $d['student'] = $student;
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first();
        
        // Récupérer tous les examens de l'étudiant
        $years = $this->exam->getExamYears($student_id);
        $d['progress_data'] = [];
        
        foreach ($years as $year_data) {
            $year = $year_data->year;
            $exams = $this->exam->getExam(['year' => $year]);
            
            foreach ($exams as $exam) {
                $record = $this->exam->getRecord([
                    'exam_id' => $exam->id,
                    'student_id' => $student_id,
                    'year' => $year
                ])->first();
                
                if ($record) {
                    $d['progress_data'][] = [
                        'exam_name' => $exam->name,
                        'year' => $year,
                        'semester' => $exam->semester,
                        'average' => $record->ave,
                        'position' => $record->pos,
                        'total' => $record->total,
                    ];
                }
            }
        }
        
        return view('pages.support_team.exam_analytics.student_progress', $d);
    }

    public function export(Request $req)
    {
        $exam_id = $req->exam_id;
        $format = $req->format ?? 'pdf';
        
        // TO DO: Implémenter l'export Excel/PDF
        return back()->with('flash_info', 'Export en développement');
    }

    private function getGradeFromMark($mark)
    {
        $tex = 'tex1'; // ou dynamique selon semester
        $total = $mark->$tex ?? 0;
        
        if ($total >= 80) return 'A';
        if ($total >= 70) return 'B';
        if ($total >= 60) return 'C';
        if ($total >= 50) return 'D';
        return 'F';
    }
}
