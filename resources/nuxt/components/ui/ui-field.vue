<template><div class="form-group row no-gutters">
    <div class="col-12 col-md-4 col-lg-3 p-2 px-0 px-md-2">
        <div>{{ label }}</div>
        <small class="d-block text-muted" v-if="help" v-html="help"></small>
    </div>
    <div class="col-12 col-md-8 col-lg-9">
        <slot name="default"></slot>
        <small class="d-block text-danger mt-1 mx-1" v-if="error">{{ error }}</small>
    </div>
</div></template>

<script>export default {
    props: {
        label: {default: ''},
        help: {default: ''},
        error: {default: ''},
    },
};</script>