<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer'
    ];

    // Relations
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(BookCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(BookCategory::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    // Accessors
    public function getTotalBooksAttribute(): int
    {
        return $this->books()->count();
    }

    // Methods
    public function isMainCategory(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasBooks(): bool
    {
        return $this->books()->exists();
    }
}
