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

                        <div class="d-flex flex-column vote-controls">
                            <a title="This answer is useful" class="vote-up">
                                <i class="fas fa-caret-up fa-3x"></i>
                            </a>
                            <span class="votes-count">1230</span>
                            <a title="This answer is not useful" class="vote-down">
                                <i class="fas fa-caret-down fa-3x"></i>
                            </a>
                            <a title="Mark this answe as best anser" class="vote-accept mt-2 vote-accepted ">
                                <i class="fas fa-check fa-2x"></i>
                            </a>
                            <span class="favorites-count">123</span>
                        </div>


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
                                <span class="text-muted">
                                    Answered {{ $answer->created_date }}
                                </span>
                                <div class="media mt-2">
                                    <a href="{{ $answer->user->url }}" class="pr-2">
                                        <img src="{{ $answer->user->avatar}}">
                                    </a>
                                    <div class="media-body mt-1">
                                        <a href="{{ $answer->user->url }}">{{ $answer->user->name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>
