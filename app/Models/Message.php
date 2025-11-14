<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'content',
        'is_read',
        'read_at',
        'file_path',
        'priority',
        'parent_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class, 'message_id');
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class, 'message_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('receiver_id', $userId);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'secondary',
            'normal' => 'primary',
            'high' => 'danger'
        ];

        return $badges[$this->priority] ?? 'primary';
    }

    public function getPriorityIconAttribute()
    {
        $icons = [
            'low' => 'icon-arrow-down8',
            'normal' => 'icon-minus3',
            'high' => 'icon-arrow-up8'
        ];

        return $icons[$this->priority] ?? 'icon-minus3';
    }

    public function isReadBy($userId)
    {
        // Si l'utilisateur est l'expéditeur, considérer comme lu
        if ($this->sender_id == $userId) {
            return true;
        }

        // Vérifier si le destinataire a lu le message
        return $this->recipients()
            ->where('recipient_id', $userId)
            ->where('is_read', true)
            ->exists();
    }
}
