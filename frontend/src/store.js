import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        currentEmail: null,
        user: JSON.parse(localStorage.getItem('user')),
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
        login(context, data) {
            return new Promise((resolve, reject) => {
                context.commit('logout');

                axios.post('/auth/oauth', {
                    'grant_type': 'password',
                    'username': data.username,
                    'password': data.password,
                    'client_id': 'app',
                    'client_secret': '',
                    'access_type': 'offline',
                })
                    .then((response) => {
                        const user = response.data;
                        context.commit('login', user);
                        localStorage.setItem('user', JSON.stringify(user));
                        resolve(user);
                    })
                    .catch((error) => {
                        context.commit('logout');
                        reject(error);
                    });
            });
        },

        logout(context) {
            return new Promise((resolve) => {
                context.commit('logout');
                localStorage.removeItem('user');
                resolve();
            });
        }
    }
})
