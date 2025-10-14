<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'card_id',
        'subtask_id',
        'user_id',
        'comment_text',
        'comment_type',
    ];

    protected $attributes = [
        'subtask_id' => null,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }
}
