<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCharacterProgress extends Model
{
    protected $table = 'user_character_progress';

    protected $fillable = [
        'user_id',
        'character_id',
        'mastery_level',
        'last_practiced_at'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
