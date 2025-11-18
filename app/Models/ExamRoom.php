<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamRoom extends Model
{
    protected $fillable = [
        'name',
        'code',
        'building',
        'capacity',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    // Relation avec les horaires d'examen
    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class, 'exam_room_id');
    }

    // Relation avec les placements d'étudiants
    public function placements()
    {
        return $this->hasMany(ExamStudentPlacement::class, 'exam_room_id');
    }

    // Obtenir le nombre d'étudiants placés pour un horaire donné
    public function getStudentCount($exam_schedule_id)
    {
        return $this->placements()
            ->where('exam_schedule_id', $exam_schedule_id)
            ->count();
    }

    // Vérifier si la salle est disponible
    public function isAvailable($exam_schedule_id)
    {
        $count = $this->getStudentCount($exam_schedule_id);
        return $count < $this->capacity;
    }

    // Scope pour les salles actives
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope par niveau
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}
