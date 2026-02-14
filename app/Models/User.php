<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'xp_total',
        'gems',
        'energy',
        'energy_replenished_at',
        'streak',
        'last_study_at',
        'avatar_url',
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
            'last_study_at' => 'datetime',
            'energy_replenished_at' => 'datetime',
        ];
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function quizSessions()
    {
        return $this->hasMany(QuizSession::class);
    }

    public function wrongAnswers()
    {
        return $this->hasMany(UserWrongAnswer::class);
    }

    public function characterProgress()
    {
        return $this->hasMany(UserCharacterProgress::class);
    }

    public function hasEnergy()
    {
        return $this->energy > 0;
    }
}
