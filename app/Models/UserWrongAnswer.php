<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWrongAnswer extends Model
{
    // protected $guarded = ['id'];
    use HasFactory;
    protected $fillable = [
        'user_id',
        'question_id',
        'wrong_count',
        'is_mastered'
    ];

    // ✅ Ini penting agar Laravel tahu 0/1 itu sama dengan false/true
    protected $casts = [
        'is_mastered' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
