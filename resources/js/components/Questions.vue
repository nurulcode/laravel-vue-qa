<template>
    <div class="card-body">
        <spinner v-if="$root.loading"></spinner>
        <div v-else-if="questions.length">
            <question-excerpt
                v-for="question in questions"
                :key="question.id"
                :question="question"
            ></question-excerpt>
        </div>

        <div v-else class="alert alert-warning">
            <strong>Sorry</strong> There are no questions available.
        </div>
        <div class="card-footer">
            <pagination :meta="meta" :links="links"></pagination>
        </div>
    </div>
</template>
<script>
import QuestionExcerpt from "./QuestionExcerpt";
import Pagination from "./Pagination";
import { EventBus } from '../event-bus';

export default {
    components: { QuestionExcerpt, Pagination },
    data() {
        return {
            questions: [],
            meta: {},
            links: {}
        };
    },

    mounted() {
        this.fetchQuestions();

        EventBus.$on("deleted", id => {
            let index = this.questions.findIndex(
                question => id === question.id
            );
            this.remove(index);
        });
    },

    watch: {
        $route: "fetchQuestions"
    },

    methods: {
        fetchQuestions() {
            axios
                .get("/questions", { params: this.$route.query })
                .then(({ data }) => {
                    this.questions = data.data;
                    this.meta = data.meta;
                    this.links = data.links;
                });
        },

        remove(index) {
            this.questions.splice(index, 1);
            this.count--;
        }
    }
};
</script>
