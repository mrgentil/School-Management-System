<?php

namespace App\Models;

use Eloquent;

class MyClass extends Eloquent
{
    protected $fillable = [
        'name', 
        'class_type_id', 
        'academic_level_id', 
        'academic_option_id', 
        'division',
        'academic_level',
        'academic_option'
    ];

    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function academicOption()
    {
        return $this->belongsTo(AcademicOption::class);
    }

    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }

    /**
     * Génère le nom complet de la classe selon le format RDC
     * Exemple: "1ère A Biochimie" ou "2ème B Électronique"
     */
    public function getFullNameAttribute()
    {
        $parts = [];
        
        // Utiliser les champs temporaires d'abord, puis les relations
        if ($this->academic_level) {
            $parts[] = $this->academic_level;
        } elseif ($this->academicLevel) {
            $parts[] = $this->academicLevel->name;
        }
        
        if ($this->division) {
            $parts[] = $this->division;
        }
        
        if ($this->academic_option) {
            $parts[] = $this->academic_option;
        } elseif ($this->academicOption) {
            $parts[] = $this->academicOption->name;
        }
        
        return implode(' ', $parts) ?: $this->name;
    }

    /**
     * Scope pour filtrer par type de classe
     */
    public function scopeByClassType($query, $classTypeId)
    {
        return $query->where('class_type_id', $classTypeId);
    }

    /**
     * Scope pour filtrer par niveau académique
     */
    public function scopeByLevel($query, $levelId)
    {
        return $query->where('academic_level_id', $levelId);
    }
}
