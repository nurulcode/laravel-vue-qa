export default {
    data() {
        return {
            editing: false
        };
    },
    methods: {
        edit() {
            this.setEditCache();
            this.editing = true;
        },
        cancel() {
            this.restoreFromCache();
            this.editing = false;
        },
        setEditCache() {},
        restoreFromCache() {},
        update() {
            axios
                .put(this.endpoint, this.payload())
                .then(res => {
                    this.bodyHtml = res.data.body_html;
                    this.toastSuccess(res)
                    this.editing = false;
                })
                .catch(err => {
                    this.toastError(res)
                });
        },
        payload() {},
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
                            this.delete();
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
        },
        delete() {},
        toastSuccess(res) {
            this.$toast.success(res.data.message, "Success", {
                timeout: 3000,
                position: "topRight"
            });
        },
        toastError(res) {
            this.$toast.error(res.response.data.message, "Error", {
                timeout: 3000,
                position: "topRight"
            });
        }
    }
};
