<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_text',
        'file_path',
        'submitted_at',
        'score',
        'teacher_feedback',
        'status'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function getIsLateAttribute()
    {
        return $this->submitted_at->isAfter($this->assignment->due_date);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'submitted' => 'info',
            'graded' => 'success',
            'late' => 'warning'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getScorePercentageAttribute()
    {
        if (!$this->score || !$this->assignment->max_score) {
            return null;
        }

        return round(($this->score / $this->assignment->max_score) * 100, 2);
    }
}
