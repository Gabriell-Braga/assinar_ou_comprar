<template><div class="form-control p-0 bg-white">
    <div class="input-group">
        <input :type="props.type"
            class="form-control border-0 bg-transparent"
            v-model="props.value"
            @input="$emit('input', props.value)"
            :placeholder="props.placeholder">
        <div class="input-group-append" v-if="props.toggleShow"><div class="input-group-btn">
            <div class="btn" @click="props.type=(props.type=='text'? 'password': 'text')">
                <i class="fas fa-eye" :class="{'fa-eye-slash':props.type=='password'}"></i>
            </div>
        </div></div>
    </div>
    <ui-password-meter v-model="props.value" v-if="props.meter" style="margin-top:-4px;"></ui-password-meter>
</div></template>

<script>export default {
    name: 'ui-password',

    props: {
        value: {default:''},
        placeholder: {default:''},
        toggleShow: {default:true},
        meter: {default:true},
        type: {default:'password'},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value, this.props);
        }},
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>
