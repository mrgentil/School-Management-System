<?php

namespace App\Models;

use Eloquent;

class Option extends Eloquent
{
    protected $fillable = ['academic_section_id', 'name', 'code', 'active'];

    public function academic_section()
    {
        return $this->belongsTo(AcademicSection::class);
    }
}
