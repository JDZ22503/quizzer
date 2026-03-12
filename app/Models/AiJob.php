<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiJob extends Model
{
    protected $primaryKey = 'job_id';

    protected $fillable = [
        'teacher_id',
        'chapter_id',
        'prompt',
        'status',
        'started_at',
        'completed_at',
        'tokens_used',
        'errors',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
