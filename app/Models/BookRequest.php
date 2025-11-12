<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookRequest extends Model
{
    protected $fillable = [
        'book_id',
        'student_id',
        'status',
        'request_date',
        'approved_by',
        'expected_return_date',
        'actual_return_date',
        'remarks'
    ];

    protected $dates = [
        'request_date',
        'expected_return_date',
        'actual_return_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    /**
     * Les valeurs de statut possibles
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_BORROWED = 'borrowed';
    const STATUS_RETURNED = 'returned';

    // Relations
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeApprouve($query)
    {
        return $query->where('status', 'approuve');
    }

    public function scopeRefuse($query)
    {
        return $query->where('status', 'refuse');
    }

    public function scopeNonNotifies($query)
    {
        return $query->where('is_notified', false);
    }

    // Accessors
    public function getStatusLibelleAttribute()
    {
        $statuses = [
            'en_attente' => 'En attente',
            'approuve' => 'Approuvé',
            'refuse' => 'Refusé'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
    
    public function getJoursRestantsAttribute()
    {
        if (!$this->expected_return_date) {
            return null;
        }
        
        $now = now();
        $returnDate = \Carbon\Carbon::parse($this->expected_return_date);
        
        if ($now > $returnDate) {
            return -$now->diffInDays($returnDate);
        }
        
        return $now->diffInDays($returnDate);
    }
    
    /**
     * Marque la demande comme approuvée
     *
     * @param int $bibliothecaireId
     * @param string|null $reponse
     * @return bool
     */
    public function marquerCommeApprouve($bibliothecaireId, $reponse = null)
    {
        return $this->update([
            'statut' => 'approuve',
            'reponse' => $reponse,
            'bibliothecaire_id' => $bibliothecaireId,
            'date_traitement' => now(),
            'is_notified' => false
        ]);
    }
    
    /**
     * Marque la demande comme refusée
     *
     * @param int $bibliothecaireId
     * @param string $raison
     * @return bool
     */
    public function marquerCommeRefuse($bibliothecaireId, $raison)
    {
        return $this->update([
            'statut' => 'refuse',
            'reponse' => $raison,
            'bibliothecaire_id' => $bibliothecaireId,
            'date_traitement' => now(),
            'is_notified' => false
        ]);
    }
    
    /**
     * Vérifie si la demande est en attente
     *
     * @return bool
     */
    public function estEnAttente()
    {
        return $this->statut === 'en_attente';
    }
    
    /**
     * Vérifie si la demande est approuvée
     *
     * @return bool
     */
    public function estApprouvee()
    {
        return $this->statut === 'approuve';
    }
    
    /**
     * Vérifie si la demande est refusée
     *
     * @return bool
     */
    public function estRefusee()
    {
        return $this->statut === 'refuse';
    }

    public function getBadgeClassAttribute()
    {
        switch (strtolower($this->attributes['status'])) {
            case self::STATUS_PENDING:
                return 'badge-warning';
            case self::STATUS_APPROVED:
                return 'badge-info';
            case self::STATUS_BORROWED:
                return 'badge-primary';
            case self::STATUS_RETURNED:
                return 'badge-success';
            case self::STATUS_REJECTED:
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    // Méthodes utilitaires
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isBorrowed()
    {
        return $this->status === self::STATUS_BORROWED;
    }

    public function isReturned()
    {
        return $this->status === self::STATUS_RETURNED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Vérifie si la demande peut être approuvée
     */
    public function canBeApproved()
    {
        return $this->isPending();
    }

    /**
     * Vérifie si la demande peut être rejetée
     */
    public function canBeRejected()
    {
        return $this->isPending() || $this->isApproved();
    }

    /**
     * Vérifie si la demande peut être marquée comme empruntée
     */
    public function canBeMarkedAsBorrowed()
    {
        return $this->isApproved();
    }

    /**
     * Vérifie si la demande peut être marquée comme retournée
     */
    public function canBeMarkedAsReturned()
    {
        return $this->isBorrowed();
    }

    /**
     * Obtient les colonnes fillables du modèle
     */
    public static function getFillableColumns()
    {
        return (new static())->getFillable();
    }

    /**
     * Obtient les statuts disponibles
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_APPROVED => 'Approuvé',
            self::STATUS_REJECTED => 'Rejeté',
            self::STATUS_BORROWED => 'Emprunté',
            self::STATUS_RETURNED => 'Retourné',
        ];
    }
    
    /**
     * Vérifie si la demande est en retard
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->expected_return_date 
            && $this->status === self::STATUS_BORROWED 
            && now()->gt($this->expected_return_date);
    }
    
    /**
     * Vérifie si la demande peut être annulée
     *
     * @return bool
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_APPROVED
        ]);
    }
    
    /**
     * Marquer la demande comme retournée
     *
     * @param int|null $returnedById ID de l'utilisateur qui effectue le retour
     * @return bool
     */
    public function markAsReturned($returnedById = null)
    {
        if (!$this->canBeMarkedAsReturned()) {
            return false;
        }
        
        $this->status = self::STATUS_RETURNED;
        $this->actual_return_date = now();
        $this->approved_by = $returnedById;
        
        // Mettre à jour la disponibilité du livre
        if ($this->book) {
            $this->book->available = true;
            $this->book->save();
        }
        
        return $this->save();
    }
    
    /**
     * Annuler la demande
     *
     * @return bool
     */
    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }
        
        $this->status = self::STATUS_REJECTED;
        $this->remarks = $this->remarks . "\nDemande annulée par l'utilisateur le " . now()->format('d/m/Y H:i');
        
        // Si le livre était approuvé, le rendre à nouveau disponible
        if ($this->status === self::STATUS_APPROVED && $this->book) {
            $this->book->available = true;
            $this->book->save();
        }
        
        return $this->save();
    }
}
