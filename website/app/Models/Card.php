<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    protected $fillable = [
        'board_id',
        'user_id',
        'card_title',
        'slug',
        'description',
        'position',
        'due_date',
        'status',
        'priority',
        'estimated_hours',
        'actual_hours',
        'started_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'started_at' => 'datetime',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2'
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Card_Assigment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(Time_Log::class);
    }

    // In website/app/Models/Card.php
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'card_assignments');
    }

    /**
     * Boot method untuk menambahkan event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Event listener ketika card diupdate
        static::updated(function ($card) {
            // Jika card status berubah menjadi 'done', update semua subtask yang masih 'in_progress'
            if ($card->status === 'done' && $card->isDirty('status')) {
                $card->subtasks()
                    ->where('status', 'in_progress')
                    ->update(['status' => 'done']);
            }
        });
    }

    /**
     * Method untuk manually complete semua subtasks
     */
    public function completeAllSubtasks()
    {
        $updatedCount = $this->subtasks()
            ->where('status', 'in_progress')
            ->update(['status' => 'done']);

        return $updatedCount;
    }

    /**
     * Get count of subtasks that will be auto-completed
     */
    public function getPendingSubtasksCount()
    {
        return $this->subtasks()
            ->where('status', 'in_progress')
            ->count();
    }

    /**
     * Check if card has any in-progress subtasks
     */
    public function hasInProgressSubtasks()
    {
        return $this->subtasks()
            ->where('status', 'in_progress')
            ->exists();
    }
}
