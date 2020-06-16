<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div v-if="!editing" class="card-title">
                        <div class="d-flex align-items-center">
                            <h2>{{ title }}</h2>
                            <div class="ml-auto">
                                <a
                                    href="/questions"
                                    class="btn btn-outline-secondary"
                                    >Back</a
                                >
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="media">
                        <vote
                            v-if="!editing"
                            :model="question"
                            name="question"
                        ></vote>
                        <div class="media-body  text-justify">
                            <form v-if="editing" @submit.prevent="update">
                                <div class="form-group">
                                    <label for="question-title">Titile</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="question-title"
                                        v-model="title"
                                        required
                                    />
                                </div>

                                <div class="form-group">
                                    <label>Question</label>

                                    <textarea
                                        v-model="body"
                                        rows="10"
                                        class="form-control"
                                        required
                                    ></textarea>
                                </div>
                                <button
                                    class="btn btn-info btn-sm"
                                    :disabled="isInvalid"
                                >
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
                            <div v-else>
                                <div class="d-flex align-items-center">
                                    <div class="mr-4">
                                        <div v-html="bodyHtml"></div>
                                    </div>
                                    <div class="ml-auto">
                                        <a
                                            v-if="authorize('modify', question)"
                                            @click.prevent="edit"
                                            class="btn btn-outline-info btn-sm btn-block"
                                            >Edit</a
                                        >
                                        <a
                                            v-if="authorize('deleteQuestion', question)"
                                            @click.prevent="destroy"
                                            class="btn btn-outline-danger btn-sm btn-block mt-2"
                                            >Delete</a
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="float-right">
                                <user-info :model="question" label="Asked">
                                </user-info>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vote from './Vote'
import UserInfo from './UserInfo'

export default {
    props: ["question"],
    components: {
        Vote, UserInfo
    },
    data() {
        return {
            title: this.question.title,
            bodyHtml: this.question.body_html,

            editing: false,
            body: this.question.body,
            id: this.question.id,
            beforeEditCache: null
        };
    },
    methods: {
        edit() {
            this.beforeEditCache = {
                body : this.body,
                title : this.title
            };
            this.editing = true;
        },
        cancel() {
            this.body = this.beforeEditCache.body;
            this.title = this.beforeEditCache.title;
            this.editing = false;
        },
        update() {
            axios
                .put(this.endpoint, {
                    body: this.body,
                    title: this.title
                })
                .then(res => {
                    console.log(res);
                    this.bodyHtml = res.data.body_html;
                    this.editing = false;

                    this.$toast.success(res.data.message, "Success", {
                        timeout: 3000,
                        position: "topRight"
                    });
                })
                .catch(err => {
                    this.$toast.error(res.response.data.message, "Error", {
                        timeout: 3000,
                        position: "topRight"
                    });
                });
        },
        destroy() {
            this.$toast.question("Are you sure about that?", "Confirm", {
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: "Hey",
                message: "",
                position: "center",
                buttons: [
                    [
                        "<button><b>YES</b></button>",
                        (instance, toast) => {
                            axios
                                .delete(this.endpoint)
                                .then(res => {
                                    this.$toast.success(
                                        res.data.message,
                                        "Success",
                                        { timeout: 2500 }
                                    );
                                })


                            setTimeout(() => {
                                window.location.href = "/questions";
                            }, 3000);

                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        true
                    ],
                    [
                        "<button>NO</button>",
                        function(instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        }
                    ]
                ],
                onClosing: function(instance, toast, closedBy) {
                    console.info("Closing | closedBy: " + closedBy);
                },
                onClosed: function(instance, toast, closedBy) {
                    console.info("Closed | closedBy: " + closedBy);
                }
            });
        }
    },
    computed: {
        isInvalid() {
            return this.body.length < 1 || this.title.length < 1;
        },
        endpoint() {
            return `/questions/${this.id}`;
        }
    }
};
</script>
