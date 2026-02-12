<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_id', 'name', 'topic_keyword', 'guide_md', 'order_sequence'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    // Cek progress user tertentu di unit ini
    public function progressForUser($userId)
    {
        return $this->hasOne(UserProgress::class)->where('user_id', $userId);
    }
}
