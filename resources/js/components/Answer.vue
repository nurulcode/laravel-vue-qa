<template>
    <div class="media post">
        <vote :model="answer" name="answer"></vote>
        <div class="media-body text-justify p-2">
            <form
                v-show="authorize('modify', answer) && editing"
                @submit.prevent="update"
            >
                <div class="form-group">
                    <m-editor :body="body" :name="uniqueName">
                        <textarea
                            v-model="body"
                            rows="5"
                            class="form-control"
                        ></textarea>
                    </m-editor>
                </div>
                <button class="btn btn-info btn-sm" :disabled="isInvalid">
                    Update
                </button>
                <button
                    class="btn btn-warning btn-sm"
                    @click="cancel"
                    type="button"
                >
                    Cancle
                </button>
            </form>
            <div v-show="!editing">
                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        <div v-html="bodyHtml" ref="bodyHtml"></div>
                    </div>
                    <div class="ml-auto">
                        <a
                            v-if="authorize('modify', answer)"
                            @click.prevent="edit"
                            class="btn btn-outline-info btn-sm btn-block"
                        >
                            Edit
                        </a>
                        <a
                            v-if="authorize('modify', answer)"
                            @click.prevent="destroy"
                            class="btn btn-outline-danger btn-sm btn-block mt-2"
                        >
                            Delete
                        </a>
                    </div>
                </div>
            </div>Answers
            <div class="float-right">
                <user-info :model="answer" label="Answered"> </user-info>
            </div>
        </div>
    </div>
</template>

<script>
import modification from "../mixins/modification";

export default {
    props: ["answer"],

    mixins: [modification],
    data() {
        return {
            body: this.answer.body,
            bodyHtml: this.answer.body_html,
            id: this.answer.id,
            questionId: this.answer.question_id,
            beforeEditCache: null
        };
    },

    methods: {
        setEditCache() {
            this.beforeEditCache = this.body;
        },
        restoreFromCache() {
            this.body = this.beforeEditCache;
        },
        payload() {
            return {
                body: this.body
            };
        },
        delete() {
            axios
                .delete(this.endpoint)
                .then(res => {
                    $(this.$el).fadeOut(1000, () => {
                        this.$emit("deleted");
                        this.$toast.success(res.data.message, "Success", {
                            timeout: 3000,
                            position: "topRight"
                        });
                    });
                })
                .catch(err => {
                    this.$toast.error(res.response.data.message, "Error", {
                        timeout: 3000,
                        position: "topRight"
                    });
                });
        }
    },
    computed: {
        isInvalid() {
            return this.body.length < 1;
        },
        endpoint() {
            return `/questions/${this.questionId}/answers/${this.id}`;
        },
        uniqueName() {
            return `answer-${this.id}`;
        }
    }
};
</script>
