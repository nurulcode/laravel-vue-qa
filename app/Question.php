<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    protected $fillable = ['title', 'body'];
    // relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function answers()
    {
        return $this->hasMany(Answer::class);
    }


    // setAttribute
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // getAttribute
    public function getUrlAttribute()
    {
        return route('questions.show', $this->slug);
    }

    public function getCreatedDate()
    {
        // format(d/m/Y)
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if ($this->answers_count > 0) {
            if ($this->best_answer_id) {
                return 'answered-accepted';
            }
            return 'answered';
        }
        return 'unanswered';
    }

    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

}
