<template><div class="ui-btn-dropdown">
    <div style="position:relative;">
        <div @click="props.show=!props.show">
            <slot name="button">
                <button type="button" class="btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </slot>
        </div>

        <div v-if="props.show" :style="`position:absolute; top:100%;`+(props.right? 'right:0px;': 'left:0px;')+`z-index:99;`">
            <slot name="dropdown"></slot>
        </div>
    </div>
</div></template>

<script>export default {
    props: {
        value: {default: () => ({})},
        show: {default: false},
        right: {default: false},
    },

    methods: {
        // 
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>
