<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Sign Up
                </div>
                <div class="card-body">
                    <b-form @submit="signup">
                        <b-form-group label="Email" label-for="signupEmail">
                            <b-form-input id="signupEmail" type="email" v-model="form.email" required></b-form-input>
                        </b-form-group>
                        <b-form-group label="Password" label-for="signupPassword">
                            <b-form-input id="signupPassword" type="password" v-model="form.password" required></b-form-input>
                        </b-form-group>
                        <b-button type="submit" variant="primary">Sign Up</b-button>
                    </b-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Signup',
        data() {
            return {
                form: {
                    email: null,
                    password: null
                }
            }
        },
        methods: {
            signup(event) {
                event.preventDefault();
                alert('Sign up with ' + this.$data.form.email);
                return false;
            }
        }
    }
</script>