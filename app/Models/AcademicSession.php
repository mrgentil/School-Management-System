<?php

namespace App\Models;

use App\Helpers\Qs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'description',
        'total_students',
        'total_teachers',
        'total_classes',
        'average_score',
        'registration_start',
        'registration_end',
        'exam_start',
        'exam_end',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'exam_start' => 'date',
        'exam_end' => 'date',
        'is_current' => 'boolean',
    ];

    // Statuts
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_UPCOMING = 'upcoming';

    /**
     * Obtenir la session actuelle
     */
    public static function current()
    {
        return self::where('is_current', true)->first();
    }

    /**
     * Définir cette session comme actuelle
     */
    public function setAsCurrent()
    {
        // Désactiver toutes les autres
        self::where('is_current', true)->update(['is_current' => false]);
        
        // Activer celle-ci
        $this->update(['is_current' => true, 'status' => self::STATUS_ACTIVE]);

        // Mettre à jour le setting
        Setting::updateOrCreate(
            ['type' => 'current_session'],
            ['description' => $this->name]
        );
    }

    /**
     * Archiver la session
     */
    public function archive()
    {
        $this->update([
            'status' => self::STATUS_ARCHIVED,
            'is_current' => false,
        ]);
    }

    /**
     * Calculer et mettre à jour les statistiques
     */
    public function updateStatistics()
    {
        $this->update([
            'total_students' => StudentRecord::where('session', $this->name)->count(),
            'total_teachers' => User::where('user_type', 'teacher')->count(),
            'total_classes' => MyClass::count(),
            'average_score' => Mark::where('year', $this->name)
                ->whereNotNull('p1_avg')
                ->avg('p1_avg'),
        ]);
    }

    /**
     * Obtenir les étudiants de cette session
     */
    public function students()
    {
        return StudentRecord::where('session', $this->name)->with(['user', 'my_class']);
    }

    /**
     * Vérifier si la session est active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Vérifier si la session est archivée
     */
    public function isArchived()
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    /**
     * Générer le nom de la prochaine session
     */
    public static function getNextSessionName()
    {
        $current = self::current();
        if (!$current) {
            $year = date('Y');
            return $year . '-' . ($year + 1);
        }

        $parts = explode('-', $current->name);
        return (intval($parts[0]) + 1) . '-' . (intval($parts[1]) + 1);
    }

    /**
     * Obtenir toutes les sessions ordonnées
     */
    public static function getAllOrdered()
    {
        return self::orderByDesc('name')->get();
    }

    /**
     * Badge de statut pour l'affichage
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => '<span class="badge badge-success">Active</span>',
            self::STATUS_ARCHIVED => '<span class="badge badge-secondary">Archivée</span>',
            self::STATUS_UPCOMING => '<span class="badge badge-info">À venir</span>',
            default => '<span class="badge badge-light">Inconnu</span>',
        };
    }

    /**
     * Badge session courante
     */
    public function getCurrentBadgeAttribute()
    {
        return $this->is_current 
            ? '<span class="badge badge-primary ml-2">📍 Courante</span>' 
            : '';
    }
}
