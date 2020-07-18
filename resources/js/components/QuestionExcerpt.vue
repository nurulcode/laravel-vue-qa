<template>
    <div class="media post">
        <div class="d-flex flex-column counters">
            <div class="vote">
                <strong>{{ question.votes_count }}</strong>
                {{ str_plural("vote", question.votes_count) }}
            </div>
            <div :class="statusClasses">
                <strong>{{ question.answers_count }}</strong>
                {{ str_plural("answer", question.answers_count) }}
            </div>
            <div class="view">
                {{ question.views + " " + str_plural("view", question.views) }}
            </div>
        </div>
        <div class="media-body">
            <div class="d-flex align-items-center">
                <h3 class="mt-0 text-justify mr-2">
                    <router-link
                        :to="{
                            name: 'questions.show',
                            params: { slug: question.slug }
                        }"
                        >{{ question.title }}</router-link
                    >
                </h3>
                <div class="ml-auto">
                    <router-link
                        :to="{
                            name: 'questions.edit',
                            params: { id: question.id }
                        }"
                        v-if="authorize('modify', question)"
                        class="btn btn-outline-info btn-sm btn-block"
                    >
                        Edit
                    </router-link>

                    <button
                        v-if="authorize('deleteQuestion', question)"
                        class="btn btn-outline-danger btn-sm btn-block mt-2"
                        @click="destroy"
                    >
                        Delete
                    </button>
                </div>
            </div>
            <p class="lead">
                Asked by
                <a href="#">
                    {{ question.user.name }}
                </a>
                <small class="text-muted">{{ question.created_date }}</small>
            </p>
            <div class="excerpt text-justify">{{ question.excerpt }}</div>
        </div>
    </div>
</template>

<script>
import destroy from "../mixins/destory";
export default {
    props: ["question"],
    mixins: [destroy],
    methods: {
        str_plural(str, count) {
            return str + (count > 1 ? "s" : "");
        },

        delete() {
            axios.delete("/questions/" + this.question.id).then(({ data }) => {
                this.$toast.success(data.message, "Success", {
                    timeout: 3000,
                    position: "topRight"
                });

                this.$emit("deleted");
            });
        }
    },
    computed: {
        statusClasses() {
            return ["status", this.question.status];
        }
    }
};
</script>
