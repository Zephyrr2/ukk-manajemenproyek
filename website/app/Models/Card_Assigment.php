<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card_Assigment extends Model
{
    protected $table = 'card_assignments';

    protected $fillable = [
        'card_id',
        'user_id',
        'assigned_at',
        'assignment_status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    
}
