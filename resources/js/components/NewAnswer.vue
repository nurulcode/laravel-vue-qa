<template>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Your Answer</h2>
                    </div>
                </div>

                <div class="card-body">
                    <form @submit.prevent="create">
                        <div class="form-group">
                            <textarea
                                v-model="body"
                                name="body"
                                class="form-control"
                                rows="5"
                                required
                            ></textarea>
                        </div>

                        <div class="form-group">
                            <button
                                :disabled="isInvalid"
                                class="btn btn-outline-primary"
                                type="submit"
                            >
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["questionId"],
    data() {
        return {
            body: ""
        };
    },
    methods: {
        create() {
            axios.post(this.endpoint, { body: this.body }).then(({ data }) => {
                this.$toast.success(data.message, "Success", {
                    timeout: 3000,
                    position: "topRight"
                });
                this.body = "";

                this.$emit("created", data.answer);
            });
        }
    },
    computed: {
        isInvalid() {
            return !this.signedIn || this.body.length < 10;
        },
        endpoint() {
            return `/questions/${this.questionId}/answers	`;
        }
    }
};
</script>
