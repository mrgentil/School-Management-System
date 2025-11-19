<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicOption extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'class_type_id',
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

    public function getDisplayNameAttribute()
    {
        return $this->code ? "{$this->name} ({$this->code})" : $this->name;
    }
}
