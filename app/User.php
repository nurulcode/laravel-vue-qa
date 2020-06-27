<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    protected $appends = ['url', 'avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relasi
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function Posts()
    {
        $type = request()->get('type');

        if ($type === 'questions') {
            $posts =  $this->questions()->get();
        } else {
            $posts = $this->answers()->with('question')->get();
            // $posts = $this->answers()->get();


            if ($type !== 'answers') {
                $posts2 = $this->questions()->get();
                $posts = $posts->merge($posts2);
            }
        }

        $data = collect();
        foreach ($posts as $post) {
            $item = [
                'votes_count' => $post->votes_count,
                'created_at' => $post->created_at->format('M d Y')
            ];

            if ($post instanceof Answer) {
                $item['type'] = 'A';
                $item['title'] = $post->question->title;
                $item['accepted'] = $post->question->best_answer_id === $post->id ? true : false;
            } elseif ($post instanceof Question) {
                $item['type'] = 'Q';
                $item['title'] = $post->title;
                $item['accepted'] = (bool) $post->best_answer_id;
            }

            $data->push($item);
        }
        return $data->sortBy('votes_count')->values()->all();
        // return $posts;

    }

    public function favorites()
    {
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps(); //, 'user_id', 'question_id');
    }

    // getAttribute
    public function getUrlAttribute()
    {
        return '#';
    }

    public function getAvatarAttribute()
    {
        $email = $this->email;
        $size = 32;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
    }

    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'votable')->withPivot('vote')->withTimestamps();
    }

    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'votable')->withTimestamps();
    }

    public function voteQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->voteQuestions();
        return $this->_vote($voteQuestions, $question, $vote);
        // if ($voteQuestions->where('votable_id', $question->id)->exists()) {
        //     $voteQuestions->updateExistingPivot($question, ['vote' => $vote]);
        // }
        // else {
        //     $voteQuestions->attach($question, ['vote' => $vote]);
        // }

        // $question->load('votes');
        // $downVotes = (int) $question->downVotes()->sum('vote');
        // $upVotes = (int) $question->upVotes()->sum('vote');

        // $question->votes_count = $upVotes + $downVotes;
        // $question->save();
    }

    public function voteAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();
        return $this->_vote($voteAnswers, $answer, $vote);
        // if ($voteAnswers->where('votable_id', $answer->id)->exists()) {
        //     $voteAnswers->updateExistingPivot($answer, ['vote' => $vote]);
        // }
        // else {
        //     $voteAnswers->attach($answer, ['vote' => $vote]);
        // }

        // $answer->load('votes');
        // $downVotes = (int) $answer->downVotes()->sum('vote');
        // $upVotes = (int) $answer->upVotes()->sum('vote');

        // $answer->votes_count = $upVotes + $downVotes;
        // $answer->save();
    }

    public function _vote($relationship, $model, $vote)
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
        else {
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');

        $model->votes_count = $upVotes + $downVotes;
        $model->save();

        return $model->votes_count;
    }



}
