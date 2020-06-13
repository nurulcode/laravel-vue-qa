<template>
    <div class="row mt-4" v-cloak>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>{{ title }}</h2>
                    </div>
                    <hr />
                    <answer
                        v-for="answer in answers"
                        :answer="answer"
                        :key="answer.id"
                    ></answer>

                    <div class="text-center mt-3" v-if="nextUrl">
                        <button @click.prevent="fatch(nextUrl)" class="btn btn-outline-secondary">
                            Load Answers
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Answer from "./Answer";

export default {
    props: ["question"],
    components: {
        Answer
    },
    data() {
        return {
            questionId: this.question.id,
            count: this.question.answers_count,
            answers: [],
            nextUrl: null,
        };
    },

    created() {
        this.fatch(`/questions/${this.questionId}/answers`);
    },

    methods: {
        fatch(endpoint) {
            axios.get(endpoint).then(({data}) => {
                console.log(data.next_page_url);

                this.answers.push(...data.data)
                this.nextUrl = data.next_page_url
            });
        }
    },
    computed: {
        title() {
            return this.count + " " + (this.count > 1 ? "Answers" : Answer);
        }
    }
};
</script>
