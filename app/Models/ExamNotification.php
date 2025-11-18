<?php

namespace App\Models;

use Eloquent;

class ExamNotification extends Eloquent
{
    protected $fillable = ['exam_id', 'type', 'title', 'message', 'recipients', 'sent', 'sent_at'];

    protected $casts = [
        'recipients' => 'array',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
