<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectGradeConfig extends Model
{
    protected $table = 'subject_grades_config';

    protected $fillable = [
        'my_class_id',
        'subject_id', 
        'period_max_points',
        'exam_max_points',
        'academic_year',
        'active',
        'notes'
    ];

    protected $casts = [
        'period_max_points' => 'decimal:2',
        'exam_max_points' => 'decimal:2',
        'active' => 'boolean'
    ];

    /**
     * Relation avec la classe
     */
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    /**
     * Relation avec la matière
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Scope pour l'année académique courante
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('academic_year', \App\Helpers\Qs::getSetting('current_session'));
    }

    /**
     * Scope pour les configurations actives
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour une classe spécifique
     */
    public function scopeForClass($query, $classId)
    {
        return $query->where('my_class_id', $classId);
    }

    /**
     * Scope pour une matière spécifique
     */
    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Obtenir la configuration pour une classe et matière spécifique
     */
    public static function getConfig($classId, $subjectId, $year = null)
    {
        $year = $year ?: \App\Helpers\Qs::getSetting('current_session');
        
        return self::where('my_class_id', $classId)
                   ->where('subject_id', $subjectId)
                   ->where('academic_year', $year)
                   ->where('active', true)
                   ->first();
    }

    /**
     * Obtenir toutes les configurations pour une classe
     */
    public static function getClassConfigs($classId, $year = null)
    {
        $year = $year ?: \App\Helpers\Qs::getSetting('current_session');
        
        return self::with(['subject'])
                   ->where('my_class_id', $classId)
                   ->where('academic_year', $year)
                   ->where('active', true)
                   ->orderBy('subject_id')
                   ->get();
    }

    /**
     * Créer ou mettre à jour une configuration
     */
    public static function setConfig($classId, $subjectId, $periodMax, $examMax, $year = null)
    {
        $year = $year ?: \App\Helpers\Qs::getSetting('current_session');
        
        return self::updateOrCreate(
            [
                'my_class_id' => $classId,
                'subject_id' => $subjectId,
                'academic_year' => $year
            ],
            [
                'period_max_points' => $periodMax,
                'exam_max_points' => $examMax,
                'active' => true
            ]
        );
    }

    /**
     * Calculer le pourcentage d'une note
     */
    public function calculatePercentage($obtainedPoints, $type = 'period')
    {
        $maxPoints = $type === 'exam' ? $this->exam_max_points : $this->period_max_points;
        
        if ($maxPoints <= 0) {
            return 0;
        }
        
        return round(($obtainedPoints / $maxPoints) * 100, 2);
    }

    /**
     * Obtenir les points maximum selon le type
     */
    public function getMaxPoints($type = 'period')
    {
        return $type === 'exam' ? $this->exam_max_points : $this->period_max_points;
    }
}
