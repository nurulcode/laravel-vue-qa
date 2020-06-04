<script>
export default {
    props: ["answer"],
    data() {
        return {
            editing: false,
            body: this.answer.body,
            bodyHtml: this.answer.body_html,
            id: this.answer.id,
            questionId: this.answer.question_id,
            beforeEditCache: null
        };
    },

    methods: {
        edit() {
            this.beforeEditCache = this.body;
            this.editing = true;
        },
        cancel() {
            this.body = this.beforeEditCache;
            this.editing = false;
        },
        update() {
            axios
                .patch(this.endpoint, {
                    body: this.body
                })
                .then(res => {
                    // console.log(res);
                    this.editing = false;
                    this.bodyHtml = res.data.body_html;
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
                                    $(this.$el).fadeOut(1000, () => {
                                        this.$toast.success(
                                            res.data.message,
                                            "Success",
                                            {
                                                timeout: 3000,
                                                position: "topRight"
                                            }
                                        );
                                    });
                                })
                                .catch(err => {
                                    this.$toast.error(
                                        res.response.data.message,
                                        "Error",
                                        { timeout: 3000, position: "topRight" }
                                    );
                                });

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
            return this.body.length < 1;
        },
        endpoint() {
            return `/questions/${this.questionId}/answers/${this.id}`;
        }
    }
};
</script>
