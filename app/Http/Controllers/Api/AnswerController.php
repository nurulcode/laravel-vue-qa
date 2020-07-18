<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index(Question $question)
    {
        $answers = $question->answers()->with('user')->where(function ($q) {
        if (request()->has('excludes')) {
            $q->whereNotIn('id', request()->query('excludes'));
        }
        })->simplePaginate(3);
        return AnswerResource::collection($answers);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, Request $request)
    {
        $answer = $question->answers()->create( $request->validate([
            'body' => 'required'
            ]) + ['user_id' => Auth::id()]);

            return response()->json([
                'message' => 'Your anser has been submitted successfully',
                    // Answer::with('user')->find($answer->id)
                'answer' => new AnswerResource($answer->load('user'))
                ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);
        $answer->update( $request->validate([
            'body' => 'required'
        ]) );

        // if request json
        return response()->json([
            'message' => 'Your anser has been submitted successfully',
            'body_html' => $answer->body_html
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('delete', $answer);
        $answer->delete();

        // if request json
        return response()->json([
            'message' => 'Your anser has been removed',

        ]);

    }
}
