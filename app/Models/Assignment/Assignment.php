<?php

namespace App\Models\Assignment;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title', 'description', 'my_class_id', 'section_id', 'subject_id', 'period', 'due_date', 'max_score', 'teacher_id', 'file_path', 'status'
    ];

    protected $dates = ['due_date'];
    
    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function myClass()
    {
        return $this->belongsTo(\App\Models\MyClass::class, 'my_class_id');
    }

    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'teacher_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function students()
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'assignment_submissions',
            'assignment_id',
            'student_id'
        )->withPivot('submission', 'submitted_at', 'marks', 'feedback');
    }
}
