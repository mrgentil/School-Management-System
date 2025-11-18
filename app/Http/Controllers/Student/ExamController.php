<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepo;
use App\Repositories\ExamScheduleRepo;
use App\Repositories\StudentRepo;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected $student, $exam, $schedule;

    public function __construct(StudentRepo $student, ExamRepo $exam, ExamScheduleRepo $schedule)
    {
        $this->middleware('student');
        $this->student = $student;
        $this->exam = $exam;
        $this->schedule = $schedule;
    }

    public function index()
    {
        $student_id = Auth::id();
        $sr = $this->student->getRecord(['user_id' => $student_id])->first();

        if (!$sr) {
            return redirect()->route('dashboard')->with('flash_danger', 'Aucune information trouvée');
        }

        $current_year = Qs::getSetting('current_session');

        $d['sr'] = $sr;
        $d['my_class'] = $sr->my_class;
        $d['section'] = $sr->section;

        // Examens à venir (30 prochains jours)
        $d['upcoming_schedules'] = $this->schedule->getUpcomingSchedules($sr->my_class_id, 30)
            ->filter(function($schedule) use ($sr) {
                return $schedule->section_id == $sr->section_id || is_null($schedule->section_id);
            })
            ->take(4);

        // Résultats d'examens
        $d['exam_results'] = $this->exam->getRecord([
            'student_id' => $student_id,
            'year' => $current_year
        ])->sortByDesc('created_at')->take(5);

        // Statistiques
        $all_results = $this->exam->getRecord(['student_id' => $student_id]);
        
        $d['stats'] = [
            'total_exams' => $all_results->count(),
            'avg_general' => $all_results->count() > 0 ? round($all_results->avg('ave'), 1) : 'N/A',
            'best_position' => $all_results->count() > 0 ? $all_results->min('pos') : 'N/A',
            'upcoming_exams' => $this->schedule->getUpcomingSchedules($sr->my_class_id, 30)->count(),
        ];

        return view('pages.student.exams.index', $d);
    }
}
