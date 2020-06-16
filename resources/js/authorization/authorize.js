import policies from "./policies";

export default {
    install(Vue, option) {
        Vue.prototype.authorize = (policy, model) => {
            if (!window.Auth.signedIn) {
                return false;
            }

            if (typeof policy === "string" && typeof model === "object") {
                const user = window.Auth.user;

                return policies[policy](user, model);
                // return policies.modify(user, model)
                // authorize('modify','answer')
            }
        };

        Vue.prototype.signedIn = window.Auth.signedIn;
    }
};
