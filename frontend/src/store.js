import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        currentEmail: null,
        user: null,
    },

    getters: {
        isLoggedIn(state) {
            return !!state.user;
        }
    },

    mutations: {
        changeCurrentEmail(state, email) {
            state.currentEmail = email;
        },

        login(state, user) {
            state.user = user;
        },

        logout(state) {
            state.user = null;
        }
    },

    actions: {
        logout(context) {
            return new Promise((resolve) => {
                context.commit('logout');
                resolve();
            });
        }
    }
})
