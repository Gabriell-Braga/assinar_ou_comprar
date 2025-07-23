<template><div>
    <slot :loading="loading" :resp="resp" :error="error" :submit="submit"></slot>
</div></template>

<script>
export default {
    props: {
        value: Object,
        method: {default:'get'},
        action: {default:'/api'},
        push: {default:''},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    data() {
        return {
            loading: false,
            resp: false,
            error: false,
            props: Object.assign({}, this.$props),
        };
    },

    methods: {
        submit() {
            let method = this.$axios[this.method];
            let params = this.method=='get'? {params:this.props.value}: this.props.value;

            this.loading = `<i class="fa fa-fw fa-spin fa-spinner"></i>`;
            this.resp = false;
            this.error = false;

            method(this.action, params).then((resp) => {
                this.loading = '';
                this.resp = resp.data;
            }).catch((err) => {
                this.loading = '';
                this.error = err.response.data||{};
            });
        },
    },

    mounted() {
        if (this.props.push) {
            this.$push(this.props.push, () => {
                console.error(this.props.push);
                this.submit();
            });
        }
        else {
            this.submit();
        }
    },
}
</script>