<?php

namespace App\Http\Controllers;

use App\Http\Requests\AskQuestionRequest;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with('user')->latest()->paginate(5);

        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        // relasi in question table
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')
        $request->user()->questions()->create($request->only('title', 'body'));
        return redirect()->route('questions.index')->with('success', 'Your question has bean submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        // $question->views = $question->views + 1;
        // $question->save();

        // refactory code
        $question->increment('views');
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        // if (Gate::denies('update-question', $question)) {
        //     abort(403, 'Access Denied');
        // }
        $this->authorize('update', $question);

        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        // if (Gate::denies('update-question', $question)) {
        //     abort(403, 'Access Denied');
        // }
        $this->authorize('update', $question);
        $question->update($request->only('title', 'body'));

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Your question has been updated' ,
                'body_html' => $question->body_html
            ]);
        }

        return redirect()->route('questions.index')->with('success', 'Your question has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        // if (Gate::denies('delete-question', $question)) {
        //     abort(403, 'Access Denied');
        // }
        $this->authorize('delete', $question);
        $question->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Your question has benn deleted' ,
            ]);
        }

        return redirect()->route('questions.index')->with('success', 'Your question has benn deleted');
    }
}
