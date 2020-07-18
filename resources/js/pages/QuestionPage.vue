<template>
    <div class="container" v-if="question.id">
        <question :question="question"></question>
        <answers :question="question"></answers>
    </div>
</template>
<script>
import Answers from "../components/Answers";
import Question from "../components/Question";

export default {
    props: ["slug"],
    components: {
        Answers,
        Question
    },

    data() {
        return {
            question: {}
        };
    },
    mounted() {
        this.fetchQuestion();
    },
    methods: {
        fetchQuestion() {
            axios
                .get(`/questions/${this.slug}`)
                .then(({ data }) => {
                    this.question = data.data;
                })
                .catch(err => console.log(err));
        }
    }
};
</script>
