<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'my_class_id',
        'section_id',
        'teacher_id',
        'due_date',
        'max_score',
        'file_path',
        'status'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('my_class_id', $classId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date->isPast() && $this->status === 'active';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'closed' => 'secondary',
            'draft' => 'warning'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getSubmissionForStudent($studentId)
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}
