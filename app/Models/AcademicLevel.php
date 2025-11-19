<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicLevel extends Model
{
    protected $fillable = [
        'name',
        'display_name', 
        'class_type_id',
        'order',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function myClasses()
    {
        return $this->hasMany(MyClass::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByClassType($query, $classTypeId)
    {
        return $query->where('class_type_id', $classTypeId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }
}
