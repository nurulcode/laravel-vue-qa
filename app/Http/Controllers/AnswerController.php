<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index(Question $question)
    {
        return $question->answers()->with('user')->simplePaginate(3);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, Request $request)
    {
        // $request->validate([ 'body' => 'required' ]);

        // $question->answers()->create([
        //     'body' => $request,
        //     'user_id' => \Auth::id()
        //     ]);

        // not relasi in answers table
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')
        $answer = $question->answers()->create( $request->validate([
            'body' => 'required'
            ]) + ['user_id' => Auth::id()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Your anser has been submitted successfully',
                     // Answer::with('user')->find($answer->id)
                   'answer' => $answer->load('user')
                    ]);
            }

        return back()->with('success', 'Your anser has been submitted successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question,Answer $answer)
    {
        $this->authorize('update', $answer);
        return view('answers.edit', compact('question', 'answer'));
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
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your anser has been submitted successfully',
                'body_html' => $answer->body_html
            ]);
        }

        return redirect()->route('questions.show', $question->slug)->with('success', 'Your anser has been submitted successfully');
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
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Your anser has been removed',

            ]);
        }
        return back()->with('success', 'Your answer has been removed');
    }
}
