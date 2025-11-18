<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamStudentPlacement extends Model
{
    protected $fillable = [
        'exam_schedule_id',
        'student_id',
        'exam_room_id',
        'seat_number',
        'ranking_score',
        'performance_level',
    ];

    protected $casts = [
        'ranking_score' => 'decimal:2',
        'seat_number' => 'integer',
    ];

    // Relation avec l'horaire d'examen
    public function schedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    // Relation avec l'étudiant
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relation avec la salle
    public function room()
    {
        return $this->belongsTo(ExamRoom::class, 'exam_room_id');
    }

    // Obtenir le record de l'étudiant
    public function studentRecord()
    {
        return $this->hasOneThrough(
            StudentRecord::class,
            User::class,
            'id', // Foreign key sur users
            'user_id', // Foreign key sur student_records
            'student_id', // Local key sur placements
            'id' // Local key sur users
        );
    }
}
