<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'type', 'content', 'is_ai_generated'];

    protected $casts = [
        'content' => 'array',
        'is_ai_generated' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function wrongAnswers()
    {
        return $this->hasMany(UserWrongAnswer::class);
    }
}
