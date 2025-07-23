<template><div class="ui-input mb-3" style="position:relative;" :class="{'ui-input-full':!!props.value}" @keyup="$emit('input', props.value)" @click="$emit('input', props.value)">
    <div class="input-group form-control p-0 m-0">
        <div class="input-group-prepend" v-if="$slots.prepend">
            <slot name="prepend"></slot>
        </div>

        <div class="form-control p-0 border-0 bg-transparent" style="position:relative;">
            <label :for="props.id" v-html="props.label"
                class="form-control ui-input-label"
            ></label>

            <div class="form-control d-flex border-0 bg-transparent"
                contenteditable="true"
                style="height:auto;"
                v-if="props.type=='html'"
                @keyup="props.value=$event.target.innerHTML">&nbsp;</div>

            <div class="form-control d-flex border-0 bg-transparent"
                v-else-if="props.options.length>0">
                <input class="form-control border-0 bg-transparent"
                    type="text"
                    :id="props.id"
                    v-model="props.value" />
            </div>

            <input class="form-control border-0 bg-transparent"
                :type="props.type"
                :id="props.id"
                v-model="props.value"
                v-else />
        </div>


        <div class="input-group-append" v-if="$slots.append">
            <slot name="append"></slot>
        </div>
        
        <div class="input-group-append" v-if="props.options.length>0">
            <div class="input-group-btn">
                <button type="button" class="btn" @click="props.optionsShow=!props.optionsShow">
                    <i class="fas fa-caret-down"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Error -->
    <small class="text-danger m-0" v-if="props.error.length>0" v-html="props.error.join('<br>')"></small>

    <!-- Options -->
    <div class="bg-white" v-if="props.options.length>0 && props.optionsShow" style="position:absolute; top:100%; left:0px; width:100%; min-width:200px; z-index:3; max-height:200px; overflow:auto!important;">
        <div class="p-2" v-for="opt in props.options" @click="selectOption(opt)">
            <slot name="option" :option="opt">{{ opt }}</slot>
        </div>
    </div>
</div></template>

<script>export default {
    name: "ui-input",

    props: {
        value: {default: ''},
        type: {default: 'text'},
        label: {default: ''},
        error: {default: []},
        options: {default: ()=>([])},
        optionsShow: {default:false},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        selectOption(opt) {
            this.props.optionsShow = false;
        },

        setContentEditableValue() {
            if (this.props.type!='html') return;
            this.$el.querySelector('[contenteditable]').innerHTML = this.props.value;
        },
    },

    data() {
        let data = {};
        data.props = Object.assign({}, this.$props);
        data.props.id = data.props.id||('field-'+Math.round(Math.random()*9999));
        return data;
    },

    mounted() {
        this.setContentEditableValue();
    },
};</script>


<style>
.ui-input .input-group-text, .ui-input .input-group-btn {border-color:transparent; background:none;}
.ui-input-label {position:absolute; top:0px; left:-1px; width:100%; height:100%; z-index:2; font-weight:normal!important; border-color:transparent; background:none; display:flex; align-items:center; transition: all 300ms ease;}
.ui-input-full .ui-input-label {font-size:70%; height:20px;}
.ui-input > .input-group > .form-control {transition: all 300ms ease; padding-top:10px!important;}
.ui-input-full > .input-group > .form-control {}
</style>