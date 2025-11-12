<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'name', 'author', 'isbn', 'description', 'book_type', 'url', 'location', 
        'total_copies', 'issued_copies', 'my_class_id', 'publisher', 'publication_year',
        'edition', 'cover_image', 'file_path', 'is_available', 'pages', 'language',
        'category_id', 'subject_id', 'keywords', 'shelf_location'
    ];

    protected $casts = [
        'total_copies' => 'integer',
        'issued_copies' => 'integer',
        'my_class_id' => 'integer',
        'category_id' => 'integer',
        'subject_id' => 'integer',
        'publication_year' => 'integer',
        'pages' => 'integer',
        'is_available' => 'boolean'
    ];

    protected $appends = ['available_copies', 'cover_image_url', 'file_url'];

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(BookReservation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(BookRequest::class, 'book_id');
    }

    public function borrowedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_loans')
            ->withPivot(['borrow_date', 'due_date', 'returned_date', 'status'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }

    // Accessors
    public function getAvailableCopiesAttribute(): int
    {
        return $this->total_copies - $this->issued_copies;
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    // Methods
    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->is_available;
    }

    public function borrow(User $user, $dueDate)
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $this->increment('issued_copies');
        
        if ($this->available_copies <= 0) {
            $this->update(['is_available' => false]);
        }

        return $this->loans()->create([
            'user_id' => $user->id,
            'borrow_date' => now(),
            'due_date' => $dueDate,
            'status' => 'borrowed'
        ]);
    }

    public function returnBook(User $user)
    {
        $loan = $this->loans()
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->latest()
            ->first();

        if ($loan) {
            $loan->update([
                'returned_date' => now(),
                'status' => 'returned'
            ]);

            $this->decrement('issued_copies');
            $this->update(['is_available' => true]);

            return true;
        }

        return false;
    }

    public function reserve(User $user)
    {
        return $this->reservations()->create([
            'user_id' => $user->id,
            'reservation_date' => now(),
            'expiration_date' => now()->addDays(3), // 3 jours pour récupérer le livre
            'status' => 'pending'
        ]);
    }

    // Méthode pour gérer le retour d'un livre (version simplifiée)
    public function markAsReturned(): bool
    {
        if ($this->issued_copies > 0) {
            $this->decrement('issued_copies');
            $this->is_available = true;
            $this->save();
            return true;
        }
        return false;
    }
    
    // Mettre à jour la note moyenne du livre
    public function updateRating(): void
    {
        $this->rating = $this->reviews()->approved()->avg('rating') ?: 0;
        $this->save();
    }
    
    // Vérifier si l'utilisateur a déjà emprunté ce livre
    public function wasBorrowedBy(User $user): bool
    {
        return $this->loans()->where('user_id', $user->id)->exists();
    }
    
    // Vérifier si l'utilisateur a déjà noté ce livre
    public function wasReviewedBy(User $user): bool
    {
        return $this->reviews()->where('user_id', $user->id)->exists();
    }
}
