<?php

namespace App\Models\Assignment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assignment_id', 'student_id', 'submission', 'submitted_at', 'marks', 'feedback'
    ];

    protected $dates = ['submitted_at', 'deleted_at'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }
}
