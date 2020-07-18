<template>
    <div class="card-body">
        <div v-if="questions.length">
            <question-excerpt
                @deleted="remove(index)"
                v-for="(question, index) in questions"
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
            console.log(index);
            
            this.questions.splice(index, 1);
            this.count--;
        }
    }
};
</script>
