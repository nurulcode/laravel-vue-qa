<template>
    <div class="container">
        <question :question="question"></question>
        <answers :question="question"></answers>
    </div>
</template>
<script>
import Answers from "./Answers";
import Question from "./Question";

export default {
    props: ["question"],
    components: {
        Answers,
        Question
    }
};
</script>
