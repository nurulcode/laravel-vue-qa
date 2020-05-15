<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        // bila ada jawaban baru otomatis di di question->answers_count + 1
        static::created(function($answer) {
            $answer->question->increment('answers_count');
        });
    }
}
