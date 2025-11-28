<?php

namespace App\Models;

use Eloquent;

class MyClass extends Eloquent
{
    protected $fillable = [
        'name', 
        'class_type_id', 
        'academic_section_id', 
        'option_id', 
        'division',
        'academic_level',
        'academic_option',
        'teacher_id'
    ];

    /**
     * Titulaire de la classe (professeur responsable)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Alias pour le titulaire
     */
    public function titulaire()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function academicSection()
    {
        return $this->belongsTo(AcademicSection::class, 'academic_section_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
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
        } elseif ($this->academicSection) {
            $parts[] = $this->academicSection->name;
        }
        
        if ($this->division) {
            $parts[] = $this->division;
        }
        
        if ($this->academic_option) {
            $parts[] = $this->academic_option;
        } elseif ($this->option) {
            $parts[] = $this->option->name;
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
     * Scope pour filtrer par section académique
     */
    public function scopeByAcademicSection($query, $sectionId)
    {
        return $query->where('academic_section_id', $sectionId);
    }
    
    /**
     * Scope pour filtrer par option
     */
    public function scopeByOption($query, $optionId)
    {
        return $query->where('option_id', $optionId);
    }
}
