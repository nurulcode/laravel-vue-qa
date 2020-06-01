<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use VotableTrait;

    protected $fillable = ['body', 'user_id'];

    // relasi
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // get
    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public function getCreatedDateAttribute()
    {
        // format(d/m/Y)
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        return $this->isBest() ? 'vote-accepted' : '';
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function isBest()
    {
        return $this->id === $this->question->best_answer_id;
    }


    // method di jalankan terlebih dahulu sebelum controller
    public static function boot()
    {
        parent::boot();

        // bila ada jawaban baru otomatis di question->answers_count + 1
        static::created(function($answer) {
            $answer->question->increment('answers_count');
        });


        // bila ada jawaban di hapus otomatis di question->answers_count + 1
        static::deleted(function($answer) {
            // $question = $answer->question;
            $answer->question->decrement('answers_count');
            // if ($question->best_answer_id === $answer->id) {
            //     $question->best_answer_id = NULL;
            //     $question->save();
            // }
        });
    }

}
