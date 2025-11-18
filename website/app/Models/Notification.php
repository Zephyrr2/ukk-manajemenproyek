<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'card_id',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the card/task associated with the notification
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'task_submitted' => '📝',
            'task_approved' => '✅',
            'task_rejected' => '❌',
            'extension_request' => '⏰',
            'extension_approved' => '✅',
            'extension_rejected' => '❌',
            default => '🔔',
        };
    }

    /**
     * Get notification color based on type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'task_submitted' => 'blue',
            'task_approved' => 'green',
            'task_rejected' => 'red',
            'extension_request' => 'orange',
            'extension_approved' => 'green',
            'extension_rejected' => 'red',
            default => 'gray',
        };
    }
}
