<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'chapter_id',
        'score',
        'total',
        'accuracy',
        'type',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
