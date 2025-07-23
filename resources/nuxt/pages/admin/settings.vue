<template><div>
    <div class="row no-gutters">
        <div class="col-12 col-md-3 bg-white shadow-sm mb-3 mb-md-0">
            <div class="list-group list-group-flush">
                <nuxt-link :to="n.to" class="list-group-item" v-for="n in navs" :key="n.id">{{ n.title }}</nuxt-link>
            </div>
        </div>
        <div class="col-12 col-md-9 pl-md-3">
            <div class="bg-secondary p-2" v-for="n in navs" v-if="n.to==$route.path">{{ n.subtitle }}</div>
            <nuxt-child class="bg-white p-2 pt-3 shadow-sm"></nuxt-child>
        </div>
    </div>
</div></template>

<script>
export default {
    middleware: 'auth',
    layout: 'admin',

    data() {
        return {
            navs: [
                {
                    id: "email",
                    title: "E-mail",
                    subtitle: "Configurações de e-mail",
                    to: "/admin/settings/email",
                },
                {
                    id: "email-templates",
                    title: "Templates",
                    subtitle: "Templates de e-mail",
                    to: "/admin/settings/email-templates",
                },
                {
                    id: "email-sent",
                    title: "E-mails enviados",
                    subtitle: "Lista de e-mails enviados pelo sistema",
                    to: "/admin/settings/email-sent",
                },
            ],
        };
    },
}
</script>