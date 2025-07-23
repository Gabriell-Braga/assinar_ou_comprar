<template><div>
    <h1 class="text-center py-2">Recuperar senha</h1>
    <ui-auth-password @success="onPassword()"></ui-auth-password>
</div></template>

<script>export default {
    layout: 'auth',

    methods: {
        onPassword() {
            this.$swalSuccess('Sucesso', 'Senha alterada. Por favor, faça login.');
            this.$router.push({path:'/auth'});
        },
    },
};</script>