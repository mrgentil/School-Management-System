<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name', 'author', 'description', 'book_type', 'url', 'location', 
        'total_copies', 'issued_copies', 'my_class_id'
    ];

    protected $casts = [
        'total_copies' => 'integer',
        'issued_copies' => 'integer',
        'my_class_id' => 'integer',
    ];

    // Relation avec les demandes d'emprunt
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }

    // Relation avec la classe
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    // Vérifier si le livre est disponible
    public function isAvailable()
    {
        return ($this->total_copies - $this->issued_copies) > 0;
    }

    // Obtenir le nombre de copies disponibles
    public function getAvailableCopiesAttribute()
    {
        return $this->total_copies - $this->issued_copies;
    }

    // Réduire la quantité disponible lors d'un emprunt
    public function borrow()
    {
        if ($this->isAvailable()) {
            $this->increment('issued_copies');
            return true;
        }
        return false;
    }

    // Augmenter la quantité disponible lors d'un retour
    public function returnBook()
    {
        if ($this->issued_copies > 0) {
            $this->decrement('issued_copies');
            return true;
        }
        return false;
    }
}
