<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'class',
        'roll_number',
        'xp',
        'streak',
        'level',
        'last_active_at',
        'last_streak_at',
        'medium',
        'whatsapp_number',
        'school',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'last_active_at' => 'datetime',
        'last_streak_at' => 'datetime',
    ];

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getAccuracyAttribute()
    {
        $attempts = $this->quizAttempts()->latest()->take(10)->get();
        if ($attempts->isEmpty()) {
            return 0;
        }

        return round($attempts->avg('accuracy'));
    }

    public function checkAndResetStreak()
    {
        if (!$this->last_streak_at) {
            return;
        }

        $yesterday = now()->subDay()->startOfDay();
        if ($this->last_streak_at->startOfDay()->lessThan($yesterday)) {
            $this->streak = 0;
            $this->save();
        }
    }
}
