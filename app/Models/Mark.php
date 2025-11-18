<?php

namespace App\Models;

use App\Models\User;
use Eloquent;

class Mark extends Eloquent
{
    protected $fillable = ['t1', 't2', 't3', 't4', 'tca', 'exm', 'tex1', 'tex2', 'tex3', 'sub_pos', 'cum', 'cum_ave', 'grade_id', 'year', 'exam_id', 'subject_id', 'my_class_id', 'student_id', 'section_id', 'p1_avg', 'p2_avg', 'p3_avg', 'p4_avg', 's1_exam', 's2_exam'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
