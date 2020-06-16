<template>
<div class="container">
        <question :question="question"></question>
        <answers :question="question"></answers>
</div>

</template>
<script>
import Answers from "../components/Answers";
import Question from "../components/Question";

export default {
    props: ['question'],
    components: {
        Answers,
        Question
    }
};
</script>
