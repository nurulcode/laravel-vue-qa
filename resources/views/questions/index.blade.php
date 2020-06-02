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
                    @include('questions._excerpt')
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
