<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepo;
use App\Repositories\ExamScheduleRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    protected $exam, $schedule, $my_class, $user;

    public function __construct(ExamRepo $exam, ExamScheduleRepo $schedule, MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA');
        $this->exam = $exam;
        $this->schedule = $schedule;
        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => Qs::getSetting('current_session')]);
        $d['my_classes'] = $this->my_class->all();
        
        return view('pages.support_team.exam_schedules.index', $d);
    }

    public function show($exam_id)
    {
        $d['exam'] = $this->exam->find($exam_id);
        $d['schedules'] = $this->schedule->getScheduleByExam($exam_id);
        $d['my_classes'] = $this->my_class->all();
        $d['academic_sections'] = \App\Models\AcademicSection::with('options')->where('active', 1)->get();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['teachers'] = $this->user->getUserByType('teacher');
        
        return view('pages.support_team.exam_schedules.show', $d);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'exam_id' => 'required|exists:exams,id',
            'exam_type' => 'required|in:hors_session,session',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'option_id' => 'nullable|exists:options,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $this->schedule->create($data);
        
        return back()->with('flash_success', 'Horaire d\'examen créé avec succès');
    }

    public function update(Request $req, $id)
    {
        $data = $req->validate([
            'exam_type' => 'nullable|in:hors_session,session',
            'option_id' => 'nullable|exists:options,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'nullable|string',
            'instructions' => 'nullable|string',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
        ]);

        // Fallback si le paramètre de route {id} est vide
        $scheduleId = $id ?: $req->input('schedule_id');

        $this->schedule->update($scheduleId, $data);
        
        return back()->with('flash_success', 'Horaire mis à jour avec succès');
    }

    public function destroy($id)
    {
        // Fallback si le paramètre de route {id} est vide
        $scheduleId = $id ?: request('schedule_id');

        $this->schedule->delete($scheduleId);
        
        return back()->with('flash_success', 'Horaire supprimé avec succès');
    }

    public function addSupervisor(Request $req)
    {
        $data = $req->validate([
            'exam_schedule_id' => 'required|exists:exam_schedules,id',
            'teacher_id' => 'required|exists:users,id',
            'role' => 'required|in:primary,assistant',
            'notes' => 'nullable|string',
        ]);

        $this->schedule->addSupervisor($data);
        
        return back()->with('flash_success', 'Surveillant ajouté avec succès');
    }

    public function removeSupervisor($id)
    {
        $this->schedule->removeSupervisor($id);
        
        return back()->with('flash_success', 'Surveillant retiré avec succès');
    }

    public function calendar()
    {
        $d['exams'] = $this->exam->getExam(['year' => Qs::getSetting('current_session')]);
        $d['upcoming'] = $this->schedule->getUpcomingSchedules(null, 30);
        
        return view('pages.support_team.exam_schedules.calendar', $d);
    }
}
