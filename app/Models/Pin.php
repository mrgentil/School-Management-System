<?php

namespace App\Models;

use App\Models\User;
use App\Models\MyClass;
use Eloquent;

class Pin extends Eloquent
{
    protected $fillable = [
        'code', 'type', 'year', 'period', 'semester', 'my_class_id',
        'price', 'expires_at', 'max_uses', 'user_id', 'student_id', 
        'times_used', 'used', 'created_by'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function user($foreign = NULL)
    {
        return $this->belongsTo(User::class, $foreign);
    }

    public function student()
    {
        return $this->user('student_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    /**
     * Vérifier si le PIN est valide
     */
    public function isValid(): bool
    {
        // PIN déjà utilisé au maximum
        if ($this->times_used >= $this->max_uses) {
            return false;
        }

        // PIN expiré
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si le PIN est compatible avec un bulletin
     */
    public function isValidForBulletin($type, $periodOrSemester, $year, $classId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Vérifier l'année
        if ($this->year && $this->year !== $year) {
            return false;
        }

        // Vérifier la classe
        if ($this->my_class_id && $classId && $this->my_class_id != $classId) {
            return false;
        }

        // Vérifier la période/semestre
        if ($type === 'period' && $this->period && $this->period != $periodOrSemester) {
            return false;
        }

        if ($type === 'semester' && $this->semester && $this->semester != $periodOrSemester) {
            return false;
        }

        return true;
    }

    /**
     * Marquer le PIN comme utilisé
     */
    public function markAsUsed($userId, $studentId): void
    {
        $this->times_used = $this->times_used + 1;
        $this->user_id = $userId;
        $this->student_id = $studentId;
        $this->used = $this->times_used >= $this->max_uses ? 1 : 0;
        $this->save();
    }

    /**
     * Scope: PINs valides
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->where('used', 0)
              ->where(function ($q2) {
                  $q2->whereNull('expires_at')
                     ->orWhere('expires_at', '>', now());
              });
        });
    }

    /**
     * Scope: PINs utilisés
     */
    public function scopeUsed($query)
    {
        return $query->where('used', 1);
    }

    /**
     * Scope: Par année
     */
    public function scopeForYear($query, $year)
    {
        return $query->where(function ($q) use ($year) {
            $q->whereNull('year')->orWhere('year', $year);
        });
    }

    /**
     * Scope: Par type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}

