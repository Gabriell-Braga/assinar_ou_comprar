<template>
    <div class="form-control ui-content" contenteditable="true"
        @keyup="formControlHandle($event)"
        @click="formControlHandle($event)"
    ></div>
</template>

<style>
.ui-content.form-control {height:auto;}
</style>


<script>
export default {
    name: 'ui-content',

    props: {
        value: {default:''},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        uuid(prefix='') { return prefix+([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c => (c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))).toString(16)); },

        formControlHandle(ev) {
            this.props.value = ev.target.innerHTML;
            this.$emit('input', this.props.value);
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        }
    },

    mounted() {
        this.$el.innerHTML = this.props.value;
    },
}
</script>