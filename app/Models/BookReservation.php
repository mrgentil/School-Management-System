<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BookReservation extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'reservation_date',
        'expiration_date',
        'status',
        'notes'
    ];

    protected $dates = [
        'reservation_date',
        'expiration_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_expired' => 'boolean'
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

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
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_APPROVED)
                    ->where('expiration_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', self::STATUS_EXPIRED)
              ->orWhere('expiration_date', '<=', now());
        });
    }

    // Accessors
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiration_date->isPast() || $this->status === self::STATUS_EXPIRED;
    }

    public function getRemainingDaysAttribute(): ?int
    {
        if ($this->is_expired) {
            return null;
        }
        return now()->diffInDays($this->expiration_date, false);
    }

    // Methods
    public function approve(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update(['status' => self::STATUS_APPROVED]);
        
        // TODO: Envoyer une notification à l'utilisateur
        
        return true;
    }

    public function reject(string $reason = null): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'notes' => $reason ?: $this->notes
        ]);
        
        // TODO: Envoyer une notification à l'utilisateur
        
        return true;
    }

    public function cancel(): bool
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED])) {
            return false;
        }

        $this->update(['status' => self::STATUS_CANCELLED]);
        return true;
    }

    public function markAsExpired(): bool
    {
        if ($this->status !== self::STATUS_APPROVED) {
            return false;
        }

        $this->update(['status' => self::STATUS_EXPIRED]);
        return true;
    }

    public function convertToLoan(): ?BookLoan
    {
        if ($this->status !== self::STATUS_APPROVED || $this->is_expired) {
            return null;
        }

        $loan = BookLoan::create([
            'book_id' => $this->book_id,
            'user_id' => $this->user_id,
            'borrow_date' => now(),
            'due_date' => now()->addWeeks(2), // 2 semaines par défaut
            'status' => 'borrowed'
        ]);

        if ($loan) {
            $this->update(['status' => 'completed']);
            
            // Mettre à jour le livre
            $this->book->increment('issued_copies');
            if ($this->book->available_copies <= 0) {
                $this->book->update(['is_available' => false]);
            }
        }

        return $loan;
    }
}
