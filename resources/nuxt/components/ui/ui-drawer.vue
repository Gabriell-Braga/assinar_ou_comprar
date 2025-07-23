<template><div class="ui-drawer">
    <template v-if="renderHere">
        <slot name="content"></slot>
    </template>
    <div :class="`ui-drawer-type ui-drawer-type-${props.type} ${props.value? 'ui-drawer-type-show': null}`"
        :style="`background:${props.backdropColor};`"
        @mousedown.self="props.value=false; $emit('input', props.value);" style="animation-duration:300ms;">
        <component :is="tag" @submit.prevent="$emit('submit', $event)">
            <div class="ui-drawer-content bg-white shadow" :style="`width:${props.width};`">
                <slot name="content"></slot>
            </div>
        </component>
    </div>
</div></template>

<style>
.ui-drawer {}
.ui-drawer * {transition: all 300ms ease;}

.ui-drawer-type {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100vw;
    height: 100vh;
    z-index: 99999 !important;
    visibility: hidden;
    opacity: 0;
}

.ui-drawer-type.ui-drawer-type-show {visibility:visible; opacity:1;}

.ui-drawer-content {overflow-x:hidden; overflow-y:auto;}

/* Modal */
.ui-drawer-type-modal {display:flex; align-items: center; justify-content: center; padding: 0 !important;}
.ui-drawer-type-modal .ui-drawer-content {}

/* Drawer left */
.ui-drawer-type-drawer-left {}
.ui-drawer-type-drawer-left .ui-drawer-content {height:100vh; overflow:auto; position:absolute; top:0px; left:-100%;}
.ui-drawer-type-drawer-left.ui-drawer-type-show .ui-drawer-content {left:0px;}

/* Drawer right */
.ui-drawer-type-drawer-right {}
.ui-drawer-type-drawer-right .ui-drawer-content {height:100vh; overflow:auto; position:absolute; top:0px; right:-100%;}
.ui-drawer-type-drawer-right.ui-drawer-type-show .ui-drawer-content {right:0px;}
</style>

<script>export default {
    props: {
        value: {default: false},
        tag: {default: 'div'},
        type: {default: 'modal'}, // modal | drawer-left | drawer-right
        backdropColor: {default: '#00000055'},
        width: {default: '300px'},
        renderHere: {default: false},
    },

    watch: {
        $props: {deep: true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        toggle() {
            this.props.value? this.hide(): this.show();
        },

        show() {
            this.props.value = true;
            this.$emit('value', this.props.value);
        },

        hide() {
            this.props.value = false;
            this.$emit('value', this.props.value);
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>
