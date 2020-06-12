<answer :answer="{{ $answer }}" inline-template>
    <div class="media post">
        {{-- voteStart --}}
        {{-- @include('shared._vote', [
            'model' => $answer
        ]) --}}
        <vote :model="{{ $answer }}" name="answer"></vote>

        <div class="media-body text-justify p-2">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea v-model="body" rows="5" class="form-control"></textarea>
                </div>
                <button class="btn btn-info btn-sm" :disabled="isInvalid">Update</button>
                <button class="btn btn-warning btn-sm" @click="cancel" type="button">Cancle</button>
            </form>
            <div v-else>
                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        <div v-html="bodyHtml"></div>
                    </div>
                    <div class="ml-auto">
                        @can('update', $answer)
                            <a @click.prevent="edit" class="btn btn-outline-info btn-sm btn-block">Edit</a>
                        @endcan
                        @can('delete', $answer)
                        <a @click.prevent="destroy" class="btn btn-outline-danger btn-sm btn-block mt-2">Delete</a>
                            {{-- <form action="{{ route('questions.answers.destroy',[ $question->id, $answer->id])}}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm btn-block mt-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form> --}}
                        @endcan
                    </div>
                </div>

            </div>
            <div class="float-right">
                {{-- @include('shared._author', [
                    'model' => $answer,
                    'label' => 'Answered'
                ]) --}}
                <user-info :model="{{ $answer }}" label="Answered"> </user-info>
            </div>
        </div>
    </div>
</answer>
