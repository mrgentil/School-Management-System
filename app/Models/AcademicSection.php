<?php

namespace App\Models;

use Eloquent;

class AcademicSection extends Eloquent
{
    protected $fillable = ['name', 'code', 'active'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
