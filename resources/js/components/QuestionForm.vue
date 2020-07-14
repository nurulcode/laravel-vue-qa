<template>
    <form @submit.prevent="handleSubmit">
        <div class="form-group">
            <label for="question-title">Question Titile</label>
            <input
                type="text"
                name="title"
                :class="errorClass('title')"
                v-model="title"
            />
            <div v-if="errors['title'][0]" class="invalid-feedback">
                <strong>{{ errors["title"][0] }}</strong>
            </div>
        </div>

        <div class="form-group">
            <label for="question-body"> Explain you question </label>
            <m-editor name="question-body" :body="body">
                <textarea
                    name="body"
                    :class="errorClass('body')"
                    v-model="body"
                    rows="10"
                >
                </textarea>
                <div v-if="errors['body'][0]" class="invalid-feedback">
                    <strong> {{ errors["body"][0] }} </strong>
                </div>
            </m-editor>
        </div>

        <div class="form-group">
            <button class="btn btn-outline-primary" type="submit">
                {{ buttonText }}
            </button>
        </div>
    </form>
</template>

<script>
import { EventBus } from "../event-bus";
import MEditor from "./MEditor";
export default {
    components: {
        MEditor
    },
    props: {
        isEdit: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            title: "",
            body: "",
            errors: {
                title: [],
                body: []
            }
        };
    },
    mounted() {
        EventBus.$on("error", errors => (this.errors = errors));

        if (this.isEdit) {
            this.fetchQuestion();
        }
    },

    computed: {
        buttonText() {
            return this.isEdit ? "Update QUestion" : "Ask Question";
        }
    },

    methods: {
        handleSubmit() {
            this.$emit("submitted", {
                title: this.title,
                body: this.body
            });
        },

        errorClass(column) {
            return [
                "form-control",
                this.errors[column] && this.errors[column][0]
                    ? "is-invalid"
                    : ""
            ];
        },

        fetchQuestion() {
            axios
                .get(`/questions/${this.$route.params.id}`)
                .then(({ data }) => {
                    this.title = data.title;
                    this.body = data.body;
                })
                .catch(error => {
                    console.log(error.response);
                });
        },
    }
};
</script>
