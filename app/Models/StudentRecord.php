<?php

namespace App\Models;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRecord extends Eloquent
{
    use HasFactory;

    protected $fillable = [
        'session', 'user_id', 'my_class_id', 'section_id', 'academic_section_id', 'option_id', 'my_parent_id', 'dorm_id', 'dorm_room_no', 'adm_no', 'year_admitted', 'wd', 'wd_date', 'grad', 'grad_date', 'house', 'age'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function my_parent()
    {
        return $this->belongsTo(User::class, 'my_parent_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academic_section()
    {
        return $this->belongsTo(AcademicSection::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function dorm()
    {
        return $this->belongsTo(Dorm::class);
    }

    /**
     * Obtenir le nom complet de la classe formaté
     * Ex: "3e Mécanique A" ou "JSS 2 Scientifique Blue"
     */
    public function getFullClassNameAttribute()
    {
        $parts = [];
        
        // Nom de la classe
        if ($this->my_class) {
            $parts[] = $this->my_class->name;
        }
        
        // Option (si existe)
        if ($this->option) {
            $parts[] = $this->option->name;
        }
        
        // Division (si existe)
        if ($this->section) {
            $parts[] = $this->section->name;
        }
        
        return implode(' ', $parts);
    }
}
