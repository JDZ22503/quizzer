<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['book_id', 'title', 'chapter_number', 'content'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
