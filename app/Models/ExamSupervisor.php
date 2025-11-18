<?php

namespace App\Models;

use Eloquent;

class ExamSupervisor extends Eloquent
{
    protected $fillable = ['exam_schedule_id', 'teacher_id', 'role', 'notes'];

    public function schedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
