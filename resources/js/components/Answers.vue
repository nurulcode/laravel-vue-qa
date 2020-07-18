<template>
    <div>
        <div class="row mt-4" v-cloak v-if="count">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>{{ title }}</h2>
                        </div>
                        <hr />
                        <answer
                            v-for="(answer, index) in answers"
                            @deleted="remove(index)"
                            :answer="answer"
                            :key="answer.id"
                        ></answer>

                        <div class="text-center mt-3" v-if="theNextUrl">
                            <button
                                @click.prevent="fatch(theNextUrl)"
                                class="btn btn-outline-secondary"
                            >
                                {{ load }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <new-answer @created="add" :questionId="questionId"></new-answer>
    </div>
</template>

<script>
import Answer from "./Answer";
import NewAnswer from "./NewAnswer";
import highlight from "../mixins/highlight";
import { EventBus } from "../event-bus";

export default {
    props: ["question"],
    mixins: [highlight],
    components: {
        Answer,
        NewAnswer
    },
    data() {
        return {
            questionId: this.question.id,
            count: this.question.answers_count,
            answers: [],
            nextUrl: null,
            load: "Load Answers",
            excludeAnswers: []
        };
    },

    created() {
        this.fatch(`/questions/${this.questionId}/answers`);
    },

    methods: {
        add(answer) {
            // this.excludeAnswers.push(answer);
            this.answers.push(answer);
            this.count++;
            this.highlight();
            if (this.count === 1) {
                EventBus.$emit("answers-count-changed", this.count);
            }
        },
        remove(index) {
            this.answers.splice(index, 1);
            this.count--;

            if (this.count === 0) {
                EventBus.$emit("answers-count-changed", this.count);
            }
        },
        fatch(endpoint) {
            axios.get(endpoint).then(({ data }) => {
                this.answers.push(...data.data);
                // console.log(data);
                this.nextUrl = data.links.next;
            });
        }
    },
    computed: {
        title() {
            return this.count + " " + (this.count > 1 ? "Answers" : "Answer");
        },

        theNextUrl() {
            if (this.nextUrl && this.excludeAnswers.lenght) {
                return (
                    this.nextUrl +
                    this.excludeAnswers.map(a => "&excludes[]=" + a.id).join("")
                );
            }
            return this.nextUrl;
        }
    }
};
</script>
