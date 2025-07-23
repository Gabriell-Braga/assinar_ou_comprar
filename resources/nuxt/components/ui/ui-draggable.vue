<template><div>
    <draggable v-model="props.value" v-bind="computedBind" @end="$emit('input', props.value)">
        <transition-group
            :duration="props.animation"
            name="ui-draggable-transition-group"
            enter-active-class="animate__animated animate__fadeIn"
            leave-active-class="animate__animated animate__fadeOut"
        >
            <slot></slot>
        </transition-group>
    </draggable>
</div></template>


<script>
import draggable from 'vuedraggable';

export default {
    components: { draggable },

    props: {
        value: Array,
        handle: {default: '.div'},
        animation: {default: 200},
        handle: {default: false},
    },

    watch: {deep:true, handler(value) {
        this.props = Object.assign({}, value);
    }},

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },

    computed: {
        computedBind() {
            return {
                tag: this.props.tag,
                animation: this.props.animation,
                handle: this.props.handle,
            };
        },
    },
}
</script>