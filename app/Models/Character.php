<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = ['char', 'romaji', 'type', 'guide_stroke'];

    protected $casts = [
        'guide_stroke' => 'array',
    ];
}
