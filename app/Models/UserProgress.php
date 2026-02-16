<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'unit_id',
        'status',
        'stars',
        'high_score',
        'current_level',
        'is_completed',
        'is_locked'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
