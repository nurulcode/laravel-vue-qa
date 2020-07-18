import Vote from "../components/Vote";
import UserInfo from "../components/UserInfo";
import MEditor from "../components/MEditor";

import highlight from "./highlight";
import destroy from "./destory";


export default {
    components: {
        Vote,
        UserInfo,
        MEditor
    },
    data() {
        return {
            editing: false
        };
    },
    mixins: [highlight, destroy],
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
                    this.toastSuccess(res);
                    this.editing = false;
                })
                .then(() => {
                    this.highlight();
                })
                .catch(err => {
                    this.toastError(res);
                });
        },
        payload() {},
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
    },
    mounted() {
        this.highlight();
    }
};
