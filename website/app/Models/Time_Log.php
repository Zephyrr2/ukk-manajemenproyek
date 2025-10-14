<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Time_Log extends Model
{
    protected $table = 'time_logs';

    protected $fillable = [
        'card_id',
        'subtask_id',
        'user_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'status',
        'description',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the card (task) that this time log belongs to
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the subtask that this time log belongs to
     */
    public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class);
    }

    /**
     * Get the user that this time log belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted duration in hours and minutes
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return '0m';
        }

        $hours = intdiv($this->duration_minutes, 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /**
     * Get duration in hours (decimal)
     */
    public function getDurationHoursAttribute()
    {
        return round(($this->duration_minutes ?? 0) / 60, 2);
    }

    /**
     * Check if this time log is currently active (end_time is null)
     */
    public function getIsActiveAttribute()
    {
        return is_null($this->end_time);
    }

    /**
     * Start a new work session for a task
     */
    public static function startWorkSession($cardId, $subtaskId = null, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        // Stop any active sessions for this user
        self::stopActiveSessionsForUser($userId);

        return self::create([
            'card_id' => $cardId,
            'subtask_id' => $subtaskId,
            'user_id' => $userId,
            'start_time' => now(),
            'end_time' => null, // NULL = active session
            'duration_minutes' => 0,
            'status' => 'active',
            'description' => 'Work session started automatically',
        ]);
    }

    /**
     * Stop active work session
     */
    public function stopWorkSession()
    {
        if ($this->end_time) {
            return false; // Already stopped
        }

        $sessionDuration = now()->diffInMinutes($this->start_time);
        $totalDuration = ($this->duration_minutes ?? 0) + $sessionDuration;

        $this->update([
            'end_time' => now(),
            'duration_minutes' => $totalDuration,
            'status' => 'completed',
            'description' => "Work completed - {$this->formatted_duration}",
        ]);

        return true;
    }

    /**
     * Pause active work session
     */
    public function pauseWorkSession()
    {
        if ($this->end_time) {
            return false; // Already stopped/paused
        }

        $sessionDuration = now()->diffInMinutes($this->start_time);
        $totalDuration = ($this->duration_minutes ?? 0) + $sessionDuration;

        $this->update([
            'end_time' => now(),
            'duration_minutes' => $totalDuration,
            'status' => 'paused',
            'description' => "Work paused - {$this->formatted_duration} total",
        ]);

        return true;
    }

    /**
     * Resume paused work session (create new session record)
     */
    public function resumeWorkSession()
    {
        // Stop any other active sessions for this user
        self::stopActiveSessionsForUser($this->user_id);

        // Create a new session record for resume
        return self::create([
            'card_id' => $this->card_id,
            'subtask_id' => $this->subtask_id,
            'user_id' => $this->user_id,
            'start_time' => now(),
            'end_time' => null,
            'duration_minutes' => $this->duration_minutes, // Carry over accumulated time
            'status' => 'active',
            'description' => 'Work resumed from previous session',
        ]);
    }

    /**
     * Stop all active sessions for a user
     */
    public static function stopActiveSessionsForUser($userId)
    {
        $activeSessions = self::where('user_id', $userId)
            ->whereNull('end_time') // Active sessions have null end_time
            ->get();

        foreach ($activeSessions as $session) {
            $session->stopWorkSession();
        }

        return $activeSessions->count();
    }

    /**
     * Get active session for user and task
     */
    public static function getActiveSession($userId, $cardId, $subtaskId = null)
    {
        return self::where('user_id', $userId)
            ->where('card_id', $cardId)
            ->where('subtask_id', $subtaskId)
            ->whereNull('end_time') // Active session
            ->first();
    }

    /**
     * Get any active session for user
     */
    public static function getAnyActiveSession($userId)
    {
        return self::where('user_id', $userId)
            ->whereNull('end_time') // Active session
            ->with(['card', 'subtask'])
            ->first();
    }

    /**
     * Get total time spent on a task across all sessions
     */
    public static function getTotalTimeForTask($cardId, $subtaskId = null, $userId = null)
    {
        $query = self::where('card_id', $cardId);

        if ($subtaskId) {
            $query->where('subtask_id', $subtaskId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Include active session duration
        $sessions = $query->get();
        $totalMinutes = 0;

        foreach ($sessions as $session) {
            if ($session->end_time) {
                // Completed session
                $totalMinutes += $session->duration_minutes;
            } else {
                // Active session - calculate current duration
                $currentDuration = now()->diffInMinutes($session->start_time);
                $totalMinutes += ($session->duration_minutes ?? 0) + $currentDuration;
            }
        }

        return $totalMinutes;
    }
}
