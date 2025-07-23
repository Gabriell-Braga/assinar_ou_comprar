<template><money v-model="props.value"
    class="form-control"
    @change.native="$emit('input', props.value)"
    v-bind="{
        decimal: ',',
        thousands: '.',
        prefix: 'R$ ',
        precision: 2,
    }"
></money></template>

<script>
import { Money } from 'v-money';
export default {
    name: "ui-money",
    components: {Money},

    props: {
        value: {default: 0},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>
