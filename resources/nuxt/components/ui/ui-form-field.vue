<template><div>
    <div class="row no-gutters mb-2">
        <div class="col-12 col-md-4 p-1 p-md-2">
            <slot name="label"></slot>
        </div>

        <div class="col-12 col-md-8">
            <slot name="input"></slot>
        </div>
    </div>
</div></template>

<script>export default {
    props: {
        value: {default: ()=>({})},
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
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>