<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\ExamRepo;
use App\Repositories\ExamScheduleRepo;
use App\Repositories\StudentRepo;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $student, $exam, $schedule;

    public function __construct(StudentRepo $student, ExamRepo $exam, ExamScheduleRepo $schedule)
    {
        $this->middleware('student');
        $this->student = $student;
        $this->exam = $exam;
        $this->schedule = $schedule;
    }

    public function examSchedule()
    {
        $student_id = Auth::id();
        $sr = $this->student->getRecord(['user_id' => $student_id])->first();

        if (!$sr) {
            return redirect()->route('dashboard')->with('flash_danger', 'Aucune information d\'étudiant trouvée');
        }

        $d['sr'] = $sr;
        $d['student_id'] = $student_id;
        $d['my_class'] = $sr->my_class;
        $d['section'] = $sr->section;
        $d['exams'] = $this->exam->getExam(['year' => Qs::getSetting('current_session')]);
        $d['upcoming'] = $this->schedule->getUpcomingSchedules($sr->my_class_id, 30);
        $d['schedules'] = $this->schedule->getScheduleByClass($sr->my_class_id);

        return view('pages.student.exam_schedule', $d);
    }
}
