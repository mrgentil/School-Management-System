<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'my_class_id',
        'section_id',
        'type',
        'period',
        'semester',
        'year',
        'status',
        'published_at',
        'published_by',
        'notes',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Statuts
    const STATUS_DRAFT = 'draft';
    const STATUS_REVIEW = 'review';
    const STATUS_PUBLISHED = 'published';

    // Types
    const TYPE_PERIOD = 'period';
    const TYPE_SEMESTER = 'semester';

    /**
     * Relations
     */
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Vérifier si un bulletin est publié pour une classe/période donnée
     *
     * Règle :
     * - S'il existe une publication spécifique pour la classe, c'est SON statut
     *   qui fait foi (published/draft/review).
     * - Sinon, on regarde éventuellement une publication globale (my_class_id = null).
     */
    public static function isPublished($classId, $type, $periodOrSemester, $year)
    {
        $baseQuery = self::where('year', $year)
            ->where('type', $type);

        if ($type === self::TYPE_PERIOD) {
            $baseQuery->where('period', $periodOrSemester);
        } else {
            $baseQuery->where('semester', $periodOrSemester);
        }

        // 1) Vérifier s'il existe une entrée pour cette classe
        $classPublication = (clone $baseQuery)
            ->where('my_class_id', $classId)
            ->first();

        if ($classPublication) {
            return $classPublication->status === self::STATUS_PUBLISHED;
        }

        // 2) Sinon, on tombe sur une éventuelle publication globale
        return (clone $baseQuery)
            ->whereNull('my_class_id')
            ->where('status', self::STATUS_PUBLISHED)
            ->exists();
    }

    /**
     * Récupérer le statut de publication pour une classe
     */
    public static function getPublicationStatus($classId, $type, $periodOrSemester, $year)
    {
        $query = self::where('year', $year)
            ->where('type', $type);

        if ($type === self::TYPE_PERIOD) {
            $query->where('period', $periodOrSemester);
        } else {
            $query->where('semester', $periodOrSemester);
        }

        $publication = $query->where(function($q) use ($classId) {
            $q->where('my_class_id', $classId)
              ->orWhereNull('my_class_id');
        })->orderByRaw("CASE WHEN my_class_id IS NOT NULL THEN 0 ELSE 1 END")
          ->first();

        return $publication ? $publication->status : null;
    }

    /**
     * Publier un bulletin
     */
    public function publish($userId)
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->published_at = now();
        $this->published_by = $userId;
        $this->save();

        // Envoyer les notifications aux étudiants
        $this->notifyStudents();

        return $this;
    }

    /**
     * Notifier les étudiants de la publication
     */
    protected function notifyStudents()
    {
        $query = StudentRecord::query();

        if ($this->my_class_id) {
            $query->where('my_class_id', $this->my_class_id);
        }

        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }

        $students = $query->with('user')->get();

        $typeLabel = $this->type === self::TYPE_PERIOD 
            ? "Période {$this->period}" 
            : "Semestre {$this->semester}";

        foreach ($students as $student) {
            if ($student->user) {
                UserNotification::create([
                    'user_id' => $student->user_id,
                    'type' => 'bulletin_published',
                    'title' => 'Bulletin disponible',
                    'message' => "Votre bulletin de notes ({$typeLabel}) est maintenant disponible. Consultez-le dans la section Académique.",
                    'data' => json_encode([
                        'type' => $this->type,
                        'period' => $this->period,
                        'semester' => $this->semester,
                        'year' => $this->year,
                        'url' => route('student.grades.bulletin', [
                            'type' => $this->type,
                            'period' => $this->period,
                            'semester' => $this->semester,
                        ]),
                    ]),
                ]);
            }
        }
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where(function($q) use ($classId) {
            $q->where('my_class_id', $classId)
              ->orWhereNull('my_class_id');
        });
    }

    /**
     * Labels pour l'affichage
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_REVIEW => 'En révision',
            self::STATUS_PUBLISHED => 'Publié',
            default => 'Inconnu',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_REVIEW => 'warning',
            self::STATUS_PUBLISHED => 'success',
            default => 'dark',
        };
    }

    public function getTypeLabelAttribute()
    {
        if ($this->type === self::TYPE_PERIOD) {
            return "Période {$this->period}";
        }
        return "Semestre {$this->semester}";
    }
}
