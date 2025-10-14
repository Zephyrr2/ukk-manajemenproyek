<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'slug',
        'description',
        'deadline',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withPivot('role', 'joined_at')->withTimestamps();
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function membersWithUsers()
    {
        return $this->hasMany(ProjectMember::class)->with('user');
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function cards()
    {
        return $this->hasManyThrough(Card::class, Board::class);
    }

    public function getTasksCountAttribute()
    {
        return $this->cards()->count();
    }

    public function getCompletedTasksCountAttribute()
    {
        return $this->cards()->where('status', 'completed')->count();
    }

    public function getProgressPercentageAttribute()
    {
        $totalTasks = $this->tasks_count;
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->completed_tasks_count;
        return round(($completedTasks / $totalTasks) * 100);
    }
}
