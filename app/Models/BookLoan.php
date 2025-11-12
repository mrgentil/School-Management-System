<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BookLoan extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'borrow_date',
        'due_date',
        'returned_date',
        'status',
        'fine_amount',
        'notes'
    ];

    protected $dates = [
        'borrow_date',
        'due_date',
        'returned_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'fine_amount' => 'decimal:2',
        'is_overdue' => 'boolean'
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
    public function scopeActive($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', 'borrowed');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'borrowed' && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }
        return now()->diffInDays($this->due_date, false) * -1;
    }

    public function getFineAttribute(): float
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        $daysOverdue = $this->days_overdue;
        $finePerDay = config('library.fine_per_day', 0.50);
        
        return min($daysOverdue * $finePerDay, config('library.max_fine', 20.00));
    }

    // Methods
    public function markAsReturned(): bool
    {
        if ($this->status === 'returned') {
            return true;
        }

        $this->update([
            'returned_date' => now(),
            'status' => 'returned',
            'fine_amount' => $this->fine
        ]);

        // Mettre à jour le livre
        $this->book->markAsReturned();

        return true;
    }

    public function renew(int $daysToAdd = 14): bool
    {
        if ($this->status !== 'borrowed') {
            return false;
        }

        $this->update([
            'due_date' => $this->due_date->copy()->addDays($daysToAdd)
        ]);

        return true;
    }

    public function calculateFine(): float
    {
        if ($this->status === 'returned' || !$this->is_overdue) {
            return 0;
        }

        return $this->fine;
    }
}
