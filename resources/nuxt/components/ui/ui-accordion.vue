<template><div class="ui-accordion">
    <div class="accordion">
        <slot></slot>
    </div>
</div></template>


<script>
import Vue from 'vue';

Vue.component('ui-accordion-item', {
    props: {
        title: '',
    },

    data() {
        return {
            // id: ('accordion-item-'+Math.round(Math.random()*9999)),
        };
    },

    computed: {
        indexComputed() {
            let index = 0;
            for(let i in this.$parent.$children) {
                if (this.$parent.$children[i]==this) {
                    index = parseInt(i);
                    break;
                }
            }
            return index;
        },

        cardBodyStyleComputed() {
            let style = {};
            
            if (this.$parent.props.value==this.indexComputed) {
                // 
            }
            else {
                style.height = '0px';
                style.transform = 'scaleY(0)';
                style.transformOrigin = 'top';
            }

            return style;
        },
    },

    template: `<div class="card m-0">
        <div class="card-header p-0 bg-light">
            <button class="btn btn-block text-left font-weight-bold p-2" type="button" @click="$parent.setValue(indexComputed)">
                <i class="fas fa-angle-down float-right pt-2 pr-2" :class="{'fa-rotate-90':!$parent.props.value==indexComputed}" style="transition: all 200ms ease;"></i>
                {{ title }}
            </button>
        </div>

        <div class="collapse show" :style="cardBodyStyleComputed" style="overflow:hidden; transition: all 200ms ease; animation-duration:200ms;">
            <div class="card-body p-3">
                <slot></slot>
            </div>
        </div>

    </div>`,
});

export default {
    props: {
        value: {default:0},
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

    methods: {
        setValue(value) {
            this.props.value = value;
            this.$emit('input', this.props.value);
        },
    },
}
</script>


<style>
.ui-accordion {}
</style>