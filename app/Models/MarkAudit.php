<?php

namespace App\Models;

use Eloquent;

class MarkAudit extends Eloquent
{
    protected $table = 'marks_audit';
    
    protected $fillable = ['mark_id', 'changed_by', 'field_name', 'old_value', 'new_value', 'reason'];

    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
