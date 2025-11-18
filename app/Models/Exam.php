<?php

namespace App\Models;

use Eloquent;

class Exam extends Eloquent
{
    protected $fillable = ['name', 'semester', 'year', 'status', 'results_published', 'published_at'];

    protected $casts = [
        'results_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function notifications()
    {
        return $this->hasMany(ExamNotification::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function records()
    {
        return $this->hasMany(ExamRecord::class);
    }
}
