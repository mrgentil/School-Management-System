<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id', 'class_id', 'section_id', 'subject_id', 'status', 'date', 'taken_by'
    ];

    protected $dates = ['date'];

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(\App\Models\MyClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function takenBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'taken_by');
    }
}
