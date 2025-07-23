<template><div>
    <div class="bs-component shadow" :class="{'fixed-top':fixedTop}">
        <nav class="navbar navbar-expand-lg navbar-light bg-light container">
            <slot name="logo"></slot>

            <button class="navbar-toggler" type="button" aria-label="Toggle navigation" :class="{collapsed:!props.show}" @click="props.show=!props.show">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse animate__animated animate__fadeIn" :class="{show:props.show}" @click="closeOnClickHandler()">
                <slot name="content"></slot>
            </div>
        </nav>
    </div>
</div></template>

<script>export default {
    props: {
        fixedTop: {default: false},
        show: {default: false},
        closeOnClick: {default: true},
    },

    methods: {
        closeOnClickHandler() {
            if (!this.closeOnClick) return;
            this.props.show = false;
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>