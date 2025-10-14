<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projectMemberships()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members')->withPivot('role', 'joined_at')->withTimestamps();
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function assignedCards()
    {
        return $this->belongsToMany(Card::class, 'card_assignments')->withPivot('assignment_status', 'assigned_at', 'started_at', 'completed_at');
    }

    public function timeLogs()
    {
        return $this->hasMany(Time_Log::class);
    }
}
