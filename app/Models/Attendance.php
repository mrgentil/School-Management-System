<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'subject_id',
        'date',
        'time',
        'end_time',
        'status',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'excused' => 'info'
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}
