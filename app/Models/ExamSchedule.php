<?php

namespace App\Models;

use Eloquent;

class ExamSchedule extends Eloquent
{
    protected $fillable = [
        'exam_id', 'exam_type', 'my_class_id', 'section_id', 'option_id', 'subject_id', 
        'exam_date', 'start_time', 'end_time', 'room', 'exam_room_id',
        'instructions', 'status'
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function supervisors()
    {
        return $this->hasMany(ExamSupervisor::class);
    }

    // Relation avec la salle d'examen (pour SESSION uniquement)
    public function examRoom()
    {
        return $this->belongsTo(ExamRoom::class, 'exam_room_id');
    }

    // NOTE: Les placements sont maintenant au niveau de l'EXAMEN (exam->placements), 
    // pas au niveau de l'horaire. Un élève a UNE salle et UN numéro de place 
    // pour TOUT l'examen SESSION.

    // Vérifier si c'est un horaire SESSION (placement automatique)
    public function isSession()
    {
        return $this->exam_type === 'session';
    }

    // Vérifier si c'est un horaire HORS SESSION (salle habituelle)
    public function isHorsSession()
    {
        return $this->exam_type === 'hors_session';
    }
}
