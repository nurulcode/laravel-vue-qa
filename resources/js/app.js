require("./bootstrap");

window.Vue = require("vue");

// toast start
import VueIziToast from "vue-izitoast";
import "izitoast/dist/css/iziToast.min.css";
// toast end
import Authorization from "./authorization/authorize";
import router from "./router";
import Spinner from "./components/spinner.vue";

Vue.use(Authorization);
Vue.use(VueIziToast);

// Vue.component('question-page', require('./pages/QuestionPage.vue').default);
Vue.component("spinner", Spinner);

const app = new Vue({
    el: "#app",
    data() {
        return {
            loading: false,
            interceptor: null
        };
    },
    created() {
        this.enableInterceptor();
    },
    methods: {
        disableInterceptor() {
            axios.interceptors.request.eject(this.interceptor);
        },
        enableInterceptor() {
            this.interceptor = axios.interceptors.request.use(
                config => {
                    this.loading = true;
                    return config;
                },
                error => {
                    this.loading = false;
                    return Promise.reject(error);
                }
            );

            // Add a response interceptor
            axios.interceptors.response.use(
                response => {
                    this.loading = false;

                    return response;
                },
                error => {
                    this.loading = false;
                    return Promise.reject(error);
                }
            );
        }
    },
    router
});
