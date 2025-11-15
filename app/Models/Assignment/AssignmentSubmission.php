<?php

namespace App\Models\Assignment;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'assignment_id', 'student_id', 'submission_text', 'file_path', 'submitted_at', 'score', 'teacher_feedback', 'status'
    ];

    protected $dates = ['submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }
}
