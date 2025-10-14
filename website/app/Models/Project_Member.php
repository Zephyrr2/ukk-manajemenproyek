<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project_Member extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'joined_at',
    ];
}
