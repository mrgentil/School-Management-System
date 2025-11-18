<?php

namespace App\Repositories;

use App\Models\ExamSchedule;
use App\Models\ExamSupervisor;

class ExamScheduleRepo
{
    public function create($data)
    {
        return ExamSchedule::create($data);
    }

    public function update($id, $data)
    {
        return ExamSchedule::find($id)->update($data);
    }

    public function find($id)
    {
        return ExamSchedule::find($id);
    }

    public function delete($id)
    {
        return ExamSchedule::destroy($id);
    }

    public function getSchedule($data)
    {
        return ExamSchedule::where($data)
            ->with(['exam', 'my_class', 'section', 'subject', 'supervisors.teacher'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();
    }

    public function getScheduleByExam($exam_id)
    {
        return ExamSchedule::where('exam_id', $exam_id)
            ->with(['my_class', 'section', 'subject', 'supervisors.teacher'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();
    }

    public function getScheduleByClass($class_id, $exam_id = null)
    {
        $query = ExamSchedule::where('my_class_id', $class_id)
            ->with(['exam', 'subject', 'supervisors.teacher']);

        if ($exam_id) {
            $query->where('exam_id', $exam_id);
        }

        return $query->orderBy('exam_date')->orderBy('start_time')->get();
    }

    public function getUpcomingSchedules($class_id = null, $days = 7)
    {
        $query = ExamSchedule::where('exam_date', '>=', now())
            ->where('exam_date', '<=', now()->addDays($days))
            ->where('status', 'scheduled')
            ->with(['exam', 'my_class', 'section', 'subject']);

        if ($class_id) {
            $query->where('my_class_id', $class_id);
        }

        return $query->orderBy('exam_date')->orderBy('start_time')->get();
    }

    // Exam Supervisors
    public function addSupervisor($data)
    {
        return ExamSupervisor::create($data);
    }

    public function removeSupervisor($id)
    {
        return ExamSupervisor::destroy($id);
    }

    public function getSupervisorSchedules($teacher_id, $exam_id = null)
    {
        $query = ExamSupervisor::where('teacher_id', $teacher_id)
            ->with(['schedule.exam', 'schedule.my_class', 'schedule.subject']);

        if ($exam_id) {
            $query->whereHas('schedule', function($q) use ($exam_id) {
                $q->where('exam_id', $exam_id);
            });
        }

        return $query->get();
    }
}
