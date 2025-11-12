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
        'start_time',
        'end_time',
        'location',
        'event_type',
        'is_recurring',
        'recurrence_pattern',
        'created_by',
        'target_audience',
        'color'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                    ->orderBy('event_date', 'asc')
                    ->orderBy('start_time', 'asc');
    }

    public function scopeForAudience($query, $audience)
    {
        return $query->where(function($q) use ($audience) {
            $q->where('target_audience', 'all')
              ->orWhere('target_audience', $audience);
        });
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('d/m/Y');
    }

    public function getFormattedTimeAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
        }
        return $this->start_time ? $this->start_time->format('H:i') : '';
    }
}
