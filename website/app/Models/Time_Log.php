<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $minutes = max(0, $this->duration_minutes ?? 0); // Ensure non-negative

        if (!$minutes) {
            return '0m';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return $remainingMinutes > 0 ? "{$hours}h {$remainingMinutes}m" : "{$hours}h";
        }

        return "{$remainingMinutes}m";
    }

    /**
     * Get duration in hours (decimal)
     */
    public function getDurationHoursAttribute()
    {
        return round(max(0, $this->duration_minutes ?? 0) / 60, 2);
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

        $endTime = now();
        // IMPORTANT: Use start_time->diffInMinutes(end_time) or use abs() to get positive value
        $sessionDuration = abs($this->start_time->diffInMinutes($endTime, false));
        
        // Round to nearest minute
        $sessionDuration = round($sessionDuration);

        // Ensure duration is not negative (extra safety)
        if ($sessionDuration < 0) {
            $sessionDuration = 0;
        }

        // Calculate total duration (previous accumulated time + current session)
        $previousDuration = max(0, intval($this->duration_minutes ?? 0));
        $totalDuration = $previousDuration + $sessionDuration;
        $totalDuration = max(0, $totalDuration); // Ensure non-negative

        // IMPORTANT: Use DB update to ensure data is saved
        \DB::table('time_logs')
            ->where('id', $this->id)
            ->update([
                'end_time' => $endTime,
                'duration_minutes' => $totalDuration,
                'status' => 'completed',
                'updated_at' => now(),
            ]);

        // Reload from database
        $this->refresh();

        // Update description with formatted duration
        $hours = intdiv($totalDuration, 60);
        $minutes = $totalDuration % 60;
        $formattedDuration = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";

        \DB::table('time_logs')
            ->where('id', $this->id)
            ->update([
                'description' => "Work completed - {$formattedDuration}",
                'updated_at' => now(),
            ]);

        $this->refresh();

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

        $endTime = now();
        // IMPORTANT: Use start_time->diffInMinutes(end_time) or use abs() to get positive value
        $sessionDuration = abs($this->start_time->diffInMinutes($endTime, false));
        
        // Round to nearest minute
        $sessionDuration = round($sessionDuration);

        // Ensure duration is not negative (extra safety)
        if ($sessionDuration < 0) {
            $sessionDuration = 0;
        }

        // Calculate total duration (previous accumulated time + current session)
        $previousDuration = max(0, intval($this->duration_minutes ?? 0));
        $totalDuration = $previousDuration + $sessionDuration;
        $totalDuration = max(0, $totalDuration); // Ensure non-negative

        // IMPORTANT: Use DB update to ensure data is saved
        \DB::table('time_logs')
            ->where('id', $this->id)
            ->update([
                'end_time' => $endTime,
                'duration_minutes' => $totalDuration,
                'status' => 'paused',
                'updated_at' => now(),
            ]);

        // Reload from database
        $this->refresh();

        // Update description with formatted duration
        $hours = intdiv($totalDuration, 60);
        $minutes = $totalDuration % 60;
        $formattedDuration = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";

        \DB::table('time_logs')
            ->where('id', $this->id)
            ->update([
                'description' => "Work paused - {$formattedDuration} total",
                'updated_at' => now(),
            ]);

        $this->refresh();

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
                $totalMinutes += max(0, $session->duration_minutes ?? 0);
            } else {
                // Active session - calculate current duration
                $currentDuration = now()->diffInMinutes($session->start_time);
                $currentDuration = max(0, $currentDuration); // Ensure non-negative
                $totalMinutes += ($session->duration_minutes ?? 0) + $currentDuration;
            }
        }

        return max(0, $totalMinutes); // Ensure final result is non-negative
    }
}
