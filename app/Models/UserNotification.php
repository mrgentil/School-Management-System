<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    // Types de notifications
    const TYPE_BULLETIN_PUBLISHED = 'bulletin_published';
    const TYPE_GRADE_UPDATED = 'grade_updated';
    const TYPE_ANNOUNCEMENT = 'announcement';
    const TYPE_MESSAGE = 'message';

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }
        return $this;
    }

    /**
     * Marquer comme non lu
     */
    public function markAsUnread()
    {
        $this->is_read = false;
        $this->read_at = null;
        $this->save();
        return $this;
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Créer une notification pour un utilisateur
     */
    public static function notify($userId, $type, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Créer une notification pour plusieurs utilisateurs
     */
    public static function notifyMany(array $userIds, $type, $title, $message, $data = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = self::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
        }
        return $notifications;
    }

    /**
     * Compter les notifications non lues pour un utilisateur
     */
    public static function unreadCount($userId)
    {
        return self::where('user_id', $userId)->unread()->count();
    }

    /**
     * Icône selon le type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            self::TYPE_BULLETIN_PUBLISHED => 'icon-file-text2',
            self::TYPE_GRADE_UPDATED => 'icon-certificate',
            self::TYPE_ANNOUNCEMENT => 'icon-megaphone',
            self::TYPE_MESSAGE => 'icon-envelope',
            default => 'icon-bell',
        };
    }

    /**
     * Couleur selon le type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            self::TYPE_BULLETIN_PUBLISHED => 'success',
            self::TYPE_GRADE_UPDATED => 'info',
            self::TYPE_ANNOUNCEMENT => 'warning',
            self::TYPE_MESSAGE => 'primary',
            default => 'secondary',
        };
    }
}
