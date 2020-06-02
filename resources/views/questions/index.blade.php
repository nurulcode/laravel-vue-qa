@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>All Questions</h2>
                        <div class="ml-auto">
                            <a href="{{ route('questions.create') }}" class="btn btn-outline-secondary">Ask Question</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @forelse ($questions as $question)
                        <div class="media">
                            <div class="d-flex flex-column counters">
                                <div class="vote">
                                    <strong>{{ $question->votes_count }}</strong> {{ Str::plural('vote',$question->votes_count) }}
                                </div>
                                <div class="status {{ $question->status }}">
                                    <strong>{{ $question->answers_count }}</strong> {{ Str::plural('answer',$question->answers_count) }}
                                </div>
                                <div class="view">
                                    {{ $question->views ." ". Str::plural('view',$question->views) }}
                                </div>
                            </div>
                            <div class="media-body">
                                <div class="d-flex align-items-center">
                                    <h3 class="mt-0 text-justify mr-2"> <a href="{{ $question->url }}">{{ $question->title }}</a> </h3>
                                        <div class="ml-auto">
                                            {{-- Gate --}}
                                            {{-- @if (Auth::user()->can('update-question', $question))
                                                <a href="{{ route('questions.edit', $question->id)}}" class="btn btn-outline-info btn-sm btn-block">Edit</a>
                                            @endif --}}
                                            {{-- <a href="#" class="btn btn-outline-info btn-sm btn-block delete" question-id="{{ $question->id }}">Delete</a> --}}
                                            {{-- @if (Auth::user()->can('delete-question', $question))
                                                <form action="{{ route('questions.destroy', $question->id)}}" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm btn-block mt-2" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @endif --}}
                                            {{-- Gate --}}

                                            {{-- Policy --}}
                                            @can('update', $question)
                                                <a href="{{ route('questions.edit', $question->id)}}" class="btn btn-outline-info btn-sm btn-block">Edit</a>
                                            @endcan
                                            @can('delete', $question)
                                                <form action="{{ route('questions.destroy', $question->id)}}" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm btn-block mt-2" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @endcan
                                            {{-- Policy --}}
                                        </div>
                                </div>
                                    <p class="lead">
                                        Asked by
                                        <a href="{{ $question->user->url }}">
                                            {{ $question->user->name }}
                                        </a>
                                        <small class="text-muted">{{ $question->created_date }}</small>
                                    </p>
                                <p class="text-justify">
                                    {!! Str::limit(strip_tags($question->body), 250) !!}
                                </p>
                            </div>
                        </div>
                        <hr>
                        @empty
                            <div class="alert alert-warning">
                                <strong>Sorry</strong> There are no questions available.
                            </div>
                    @endforelse
                        {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- @section('footer')
    <script>
        $(document).ready(() => {
            $('.delete').click(function() {
            let id = $(this).attr('question-id');

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                window.location = `/api/questions/${id}/destroy`
                }
            });

          });
        })
    </script>


@endsection --}}
