<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Exam\ExamCreate;
use App\Http\Requests\Exam\ExamUpdate;
use App\Repositories\ExamRepo;
use App\Repositories\ExamScheduleRepo;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    protected $exam, $schedule;
    public function __construct(ExamRepo $exam, ExamScheduleRepo $schedule)
    {
        $this->middleware('teamSA', ['except' => ['destroy', 'dashboard'] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->exam = $exam;
        $this->schedule = $schedule;
    }

    public function dashboard()
    {
        $year = Qs::getSetting('current_session');
        $d['exams'] = $this->exam->getExam(['year' => $year]);
        
        // Statistiques
        $d['stats'] = [
            'total_exams' => $d['exams']->count(),
            'published' => $d['exams']->where('results_published', true)->count(),
            'scheduled' => $this->schedule->getSchedule(['year' => $year])->count(),
            'upcoming' => $this->schedule->getUpcomingSchedules(null, 30)->count(),
        ];

        return view('pages.support_team.exams.dashboard', $d);
    }

    public function index()
    {
        $d['exams'] = $this->exam->all();
        return view('pages.support_team.exams.index', $d);
    }

    public function store(ExamCreate $req)
    {
        $data = $req->only(['name', 'semester']);
        $data['year'] = Qs::getSetting('current_session');

        $this->exam->create($data);
        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function edit($id)
    {
        $d['ex'] = $this->exam->find($id);
        return view('pages.support_team.exams.edit', $d);
    }

    public function update(ExamUpdate $req, $id)
    {
        $data = $req->only(['name', 'semester']);

        $this->exam->update($id, $data);
        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $this->exam->delete($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }
}
