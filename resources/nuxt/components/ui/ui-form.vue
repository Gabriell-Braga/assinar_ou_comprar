<template><form class="ui-form" :method="method" :action="action" @submit.prevent="submit()">
    <div v-if="responseSuccess && ($slots.success || $scopedSlots.success)" class="alert alert-success">
        <slot name="success" :response="responseSuccess"></slot>
    </div>

    <slot name="error" :errorMessage="responseErrorMessage" :fields="responseErrorFields" v-if="responseErrorMessage">
        <div class="alert alert-danger" v-if="responseErrorMessage" v-html="responseErrorMessage"></div>
    </slot>

    <slot :loading="loading" :error="responseErrorFields" :errorMessage="responseErrorMessage" :success="responseSuccess"></slot>
</form></template>

<script>export default {
    props: {
        value: {default: ()=>({})},
        method: {default: 'post'},
        action: {default: 'api'},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        emit() {
            this.$emit('input', this.props.value);
            this.$emit('value', this.props.value);
            this.$emit('change', this.props.value);
        },

        submit() {
            let method = this.$axios[this.method];
            let params = this.method=='get'? {params:this.props.value}: this.props.value;

            this.loading = `<i class="fa fa-fw fa-spin fa-spinner"></i>`;
            this.responseSuccess = false;
            this.responseErrorMessage = false;
            this.responseErrorFields = {};

            method(this.action, params).then((resp) => {
                this.loading = '';
                this.responseSuccess = resp.data;
                this.$emit('success', this.responseSuccess);
            }).catch((err) => {
                this.loading = '';
                this.responseErrorMessage = err.response.data.message||'Erro';
                this.responseErrorFields = err.response.data.fields||{};
                for(let i in this.responseErrorFields) {
                    if (Array.isArray(this.responseErrorFields[i])) {
                        this.responseErrorFields[i] = this.responseErrorFields[i].join('<br>');
                    }
                }
                this.$emit('error', this.responseErrorFields);
            });
        },
    },

    data() {
        return {
            loading: '',
            responseSuccess: false,
            responseErrorMessage: false,
            responseErrorFields: {},
            props: Object.assign({}, this.$props),
        };
    },
};</script>