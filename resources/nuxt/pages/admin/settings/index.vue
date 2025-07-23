<template><div>
    Redirecionando...
</div></template>

<script>
export default {
    middleware: 'auth',
    layout: 'admin',
    mounted() {
        this.$router.push({
            path: '/admin/settings/email',
        });
    },
}
</script>