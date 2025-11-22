<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRemark extends Model
{
    protected $fillable = [
        'name',
        'description', 
        'sort_order',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Scope pour récupérer seulement les mentions actives
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour ordonner par sort_order puis par nom
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
