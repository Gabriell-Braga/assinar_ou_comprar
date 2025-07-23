<template><div>
    <form @submit.prevent="submit()">
        <div class="alert alert-danger" v-if="error" v-html="error"></div>

        <slot name="content">
            <div class="form-group">
                <input type="text" class="form-control" v-model="post.email" placeholder="Seu e-mail">
            </div>

            <div class="form-group">
                <ui-password v-model="post.password" placeholder="Senha" :meter="false"></ui-password>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-block">
                    <span v-if="loading"><i class="fas fa-spinner fa-spin"></i></span>
                    <span v-else>Login</span>
                </button>
            </div>
        </slot>
    </form>
</div></template>

<script>export default {
    methods: {
        submit() {
            this.error = false;
            this.loading = true;
            this.$auth.loginWith('jwt', {data:this.post}).then((resp) => {
                this.loading = false;
                if (resp.data.access_token) {
                    this.$emit('success', resp.data);
                }
            }).catch(err => {
                this.error = err.response.data.message;
                this.loading = false;
            });
        },
    },

    data() {
        return {
            loading: false,
            error: false,
            post: {
                email: '',
                password: '',
            },
        };
    },
};</script>