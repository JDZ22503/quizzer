<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'chapter_id',
        'ai_job_id',
        'type',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'difficulty',
        'status',
        'moderated_by',
        'moderated_at',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
