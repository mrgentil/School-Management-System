<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'event_type',
        'type',
        'color',
        'is_all_day',
        'is_recurring',
        'recurrence_pattern',
        'created_by',
        'target_audience',
        'send_notification',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
        'is_all_day' => 'boolean',
        'send_notification' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Types d'événements
    const TYPE_HOLIDAY = 'holiday';
    const TYPE_EXAM = 'exam';
    const TYPE_MEETING = 'meeting';
    const TYPE_EVENT = 'event';
    const TYPE_DEADLINE = 'deadline';
    const TYPE_ACTIVITY = 'activity';

    // Couleurs par type
    public static function getTypeColor($type)
    {
        return match($type) {
            self::TYPE_HOLIDAY => '#4CAF50',   // Vert
            self::TYPE_EXAM => '#F44336',      // Rouge
            self::TYPE_MEETING => '#9C27B0',   // Violet
            self::TYPE_DEADLINE => '#FF9800',  // Orange
            self::TYPE_ACTIVITY => '#00BCD4',  // Cyan
            default => '#2196F3',              // Bleu
        };
    }

    public static function getTypeLabel($type)
    {
        return match($type) {
            self::TYPE_HOLIDAY => '🏖️ Congé/Vacances',
            self::TYPE_EXAM => '📝 Examen',
            self::TYPE_MEETING => '👥 Réunion',
            self::TYPE_DEADLINE => '⏰ Date limite',
            self::TYPE_ACTIVITY => '🎉 Activité',
            default => '📅 Événement',
        };
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUpcoming($query)
    {
        $dateField = \Schema::hasColumn('school_events', 'start_date') ? 'start_date' : 'event_date';
        return $query->where($dateField, '>=', now()->toDateString())
                    ->orderBy($dateField, 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForAudience($query, $audience)
    {
        return $query->where(function($q) use ($audience) {
            $q->where('target_audience', 'all')
              ->orWhere('target_audience', $audience);
        });
    }

    public function scopeInMonth($query, $year, $month)
    {
        $dateField = \Schema::hasColumn('school_events', 'start_date') ? 'start_date' : 'event_date';
        return $query->whereYear($dateField, $year)
                    ->whereMonth($dateField, $month);
    }

    public function getStartDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : ($this->event_date ?? null);
    }

    public function getFormattedDateAttribute()
    {
        $date = $this->start_date ?? $this->event_date;
        return $date ? $date->format('d/m/Y') : '';
    }

    public function getTypeBadgeAttribute()
    {
        $type = $this->type ?? $this->event_type ?? 'event';
        $color = self::getTypeColor($type);
        $label = self::getTypeLabel($type);
        return "<span class='badge' style='background: {$color}; color: white;'>{$label}</span>";
    }
}
