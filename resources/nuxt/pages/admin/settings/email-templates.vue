<template><div>
    <ui-table v-bind="emailTemplates" :loading="loading">
        <template #header>
            <th>ID</th>
            <th>Assunto</th>
        </template>

        <template #item="{item}">
            <td>{{ item.name }}</td>
            <td>{{ item.subject }}</td>
        </template>

        <template #actions="{item}">
            <a href="javascript:;" class="btn btn-primary" @click="emailTemplateEdit=item">
                <i class="fas fa-pen"></i>
            </a>
        </template>
    </ui-table>

    <ui-form method="post" action="/api/email/save" v-model="emailTemplateEdit" #default="{loading, error, success}">
        <ui-modal v-model="emailTemplateEdit">
            <template #header>Editar</template>
            <template #body>
                <ui-field label="Assunto">
                    <input type="text" class="form-control" v-model="emailTemplateEdit.subject">
                </ui-field>

                <ui-code v-model="emailTemplateEdit.body"></ui-code>

                <div>
                    <div v-for="p in emailTemplateEdit.params" class="badge badge-success">{{ p }}</div>
                </div>
            </template>
            <template #footer>
                <button type="submit" class="btn btn-primary">
                    Salvar
                </button>
            </template>
        </ui-modal>
    </ui-form>
</div></template>

<script>
export default {
    props: {
        value: Object,
        error: [Object, Boolean],
    },

    data() {
        return {
            loading: 0,
            emailTemplates: {data:[]},
            emailTemplateEdit: false,
        };
    },

    methods: {
        getEmailTemplates() {
            this.loading++;
            this.$axios.get('/api/email/search').then(resp => {
                this.loading--;
                this.emailTemplates = resp.data||{};
            });
        },
    },

    mounted() {
        this.getEmailTemplates();
    },
};
</script>