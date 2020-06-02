<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h2>{{ $answersCount . " " . Str::plural('Answer',$answersCount) }}</h2>
                </div>
                <hr>
                @foreach ($answers as $answer)
                    <div class="media">
                        {{-- voteStart --}}
                        @include('shared._vote', [
                            'model' => $answer
                        ])

                        <div class="media-body text-justify p-2">
                            <div class="d-flex align-items-center">
                                <div class="mr-4">
                                    {!! $answer->body_html !!}
                                </div>
                                <div class="ml-auto">
                                    @can('update', $answer)
                                        <a href="{{ route('questions.answers.edit',[ $question->id, $answer->id])}}" class="btn btn-outline-info btn-sm btn-block">Edit</a>
                                    @endcan
                                    @can('delete', $answer)
                                        <form action="{{ route('questions.answers.destroy',[ $question->id, $answer->id])}}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm btn-block mt-2" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>

                            <div class="float-right">
                                @include('shared._author', [
                                    'model' => $answer,
                                    'label' => 'Answered'
                                ])
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>
