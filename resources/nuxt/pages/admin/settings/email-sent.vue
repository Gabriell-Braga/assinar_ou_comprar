<template><div>
    <ui-axios method="get" action="/api/email-sent/search" v-model="params" #default="{loading, resp, error, submit}">
        <div class="input-group" style="max-width:400px;">
            <input type="text" class="form-control" v-model="params.search" placeholder="Pesquisar">
            <div class="input-group-append"><div class="input-group-btn">
                <button type="button" class="btn btn-primary" @click="submit()">
                    <i class="fas fa-search"></i>
                </button>
            </div></div>
        </div>
        <br>

        <ui-table v-bind.sync="resp" @paginate="params.page=$event; submit()">
            <template #header>
                <th>Para</th>
                <th>Assunto</th>
                <th>Data</th>
            </template>

            <template #item="{item}">
                <td>{{ item.to.join(', ') }}</td>
                <td>{{ item.subject }}</td>
                <td><ui-timeago v-model="item.created_at"></ui-timeago></td>
            </template>

            <template #actions="{item}">
                <a href="javascript:;" class="btn btn-primary" @click="email=item">
                    <i class="fas fa-eye"></i>
                </a>
            </template>
        </ui-table>
    </ui-axios>

    <ui-modal v-model="email">
        <template #header>
            {{ email.subject }}
        </template>
        <template #body>
            <div class="bg-light p-2">Para: {{ email.to.join(', ') }}</div>
            <div class="p-2" v-html="email.body"></div>
        </template>
        <template #footer>
            <button type="button" class="btn btn-primary" @click="email=false">
                Ok
            </button>
        </template>
    </ui-modal>
</div></template>

<script>
export default {
    props: {
        value: Object,
        error: [Object, Boolean],
    },
    
    data() {
        return {
            email: false,
            params: {
                search: '',
                page: 1,
                orderby: 'id',
                order: 'desc',
            },
        };
    },
}
</script>