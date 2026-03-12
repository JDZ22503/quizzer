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
        'xp_total',
        'streak',
        'level',
        'league',
        'league_level',
        'last_active_at',
        'last_streak_at',
        'medium',
        'whatsapp_number',
        'school',
        'badges',
        'gender',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'last_active_at' => 'datetime',
        'last_streak_at' => 'datetime',
        'badges' => 'json',
    ];

    public function recalculateLeague()
    {
        $leagues = ['bronze', 'silver', 'diamond', 'champion'];
        $levels = [5, 4, 3, 2, 1];

        $currentLeague = 'bronze';
        $currentLevel = 5;

        foreach ($leagues as $league) {
            foreach ($levels as $level) {
                $key = "league_{$league}_{$level}_xp";
                $threshold = \App\Models\Setting::get($key, 0);

                if ($this->xp >= $threshold) {
                    $currentLeague = $league;
                    $currentLevel = $level;
                } else {
                    // Since thresholds should be increasing, we stop at the first one we haven't reached
                    break 2;
                }
            }
        }

        $this->league = $currentLeague;
        $this->league_level = $currentLevel;
        $this->save();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function earnedBadges()
    {
        return $this->hasMany(StudentBadge::class);
    }

    public function hasBadge($slug)
    {
        return $this->earnedBadges()->where('badge_slug', $slug)->exists();
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
        if (! $this->last_streak_at) {
            return;
        }

        $yesterday = now()->subDay()->startOfDay();
        if ($this->last_streak_at->startOfDay()->lessThan($yesterday)) {
            $this->streak = 0;
            $this->save();
        }
    }
}
