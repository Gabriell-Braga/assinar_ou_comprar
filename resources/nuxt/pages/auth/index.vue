<template><div>
    <h1 class="text-center py-2">Acesse sua conta</h1>
    <ui-auth-login @success="onLogin()"></ui-auth-login>
</div></template>

<script>
export default {
    layout: 'auth',

    methods: {
        onLogin() {
            let redirect = this.$route.query.redirect||'/dashboard';
            this.$router.push({path:redirect});
        },
    },
};</script>