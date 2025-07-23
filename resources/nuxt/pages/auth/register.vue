<template><div>
    <h1 class="text-center py-2">Faça seu cadastro <br>rápido e fácil</h1>
    <ui-auth-register @success="onRegister()"></ui-auth-register>
</div></template>

<script>export default {
    layout: 'auth',

    methods: {
        onRegister() {
            this.$swalSuccess('Sucesso', 'Cadastro efetuado. Por favor, faça login.');
            this.$router.push({path:'/auth'});
        },
    },
};</script>
