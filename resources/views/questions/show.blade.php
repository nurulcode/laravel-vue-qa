@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h2>{{ $question->title}}</h2>
                            <div class="ml-auto">
                                <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="media">
                        {{-- @include('shared._vote', [
                            'model' => $question
                        ]) --}}
                        <vote :model="{{ $question }}" name="question"></vote>
                        <div class="media-body  text-justify">
                            {!! $question->body_html !!}
                            <div class="float-right">
                                {{-- @include('shared._author', [
                                    'model' => $question,
                                    'label' => 'Asked'
                                ]) --}}
                                <user-info :model="{{ $question }}" label="Asked"> </user-info>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @include('answers._index', [
        'answersCount' => $question->answers_count,
        'answers' => $question->answers
    ]) --}}
    {{-- <answers :answers="{{ $question->answers }}" :count="{{ $question->answers_count }}"></answers> --}}
    <answers :question="{{ $question }}"></answers>

    @include('answers._create')
</div>
@endsection
