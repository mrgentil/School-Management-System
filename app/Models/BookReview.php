<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookReview extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
        'is_approved',
        'review_date'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'review_date' => 'datetime'
    ];

    // Relations
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('review_date', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedDateAttribute(): string
    {
        return $this->review_date->format('d/m/Y');
    }

    public function getRatingStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    // Methods
    public function approve(): bool
    {
        if ($this->is_approved) {
            return true;
        }

        $this->update(['is_approved' => true]);
        $this->book->updateRating();
        
        return true;
    }

    public function reject(): bool
    {
        if (!$this->is_approved) {
            return true;
        }

        $this->update(['is_approved' => false]);
        $this->book->updateRating();
        
        return true;
    }

    public function updateBookRating(): void
    {
        $this->book->updateRating();
    }

    // Événements du modèle
    protected static function booted()
    {
        static::saved(function ($review) {
            $review->book->updateRating();
        });

        static::deleted(function ($review) {
            $review->book->updateRating();
        });
    }
}
