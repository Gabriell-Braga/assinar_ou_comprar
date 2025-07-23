<template><div class="code-preview p-2" :style="`max-height:${height};`" v-if="test">
    <slot>aa</slot>
</div></template>

<script>
export default {
    props: {
        height: {default:'auto'},
    },

    data() {
        return {
            test: /[?&]test=/.test(location.search),
        };
    },
}
</script>

<style>
.code-preview {
    background: var(--dark);
    overflow: auto;
}

.code-preview, .code-preview * {
    color: #fff;
}
</style>