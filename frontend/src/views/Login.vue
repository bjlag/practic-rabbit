<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

                    <b-form @submit="login">
                        <b-form-group label="Email" label-for="loginEmail">
                            <b-form-input id="loginEmail" type="email" v-model="form.email" required></b-form-input>
                        </b-form-group>
                        <b-form-group label="Password" label-for="loginPassword">
                            <b-form-input id="loginPassword" type="password" v-model="form.password" required></b-form-input>
                        </b-form-group>
                        <b-button type="submit" variant="primary">Login</b-button>
                    </b-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: this.$store.state.currentEmail,
                    password: null
                },
                error: null
            }
        },
        methods: {
            login(event) {
                event.preventDefault();
                this.$store.commit('login', this.$data.form.email);
                alert('Login with ' + this.$data.form.email);
                return false;
            }
        }
    }
</script>