<template><div class="ui-tabs">
    <ul :class="`nav nav-tabs ui-tabs-tab ui-tabs-tab-top ui-tabs-halign-${props.halign}`" v-if="props.position=='top'">
        <template v-for="(h, hindex) in headers">
            <a class="nav-link" href="javascript:void(0);" v-html="h.title"
                :class="{active:hindex==props.value}"
                @click="setActive(hindex)"
            ></a>
        </template>
    </ul>
    
    <!-- <div class="ui-tabs-nav ui-tabs-nav-top" v-if="props.position=='top'">
        <template v-for="(h, hindex) in headers">
            <a v-bind="h.bind" v-html="h.title" @click="setActive(hindex)"></a>
        </template>
    </div> -->

    <div class="row no-gutters row-eq-height">
        <div class="col-2" v-if="props.position=='left'">
            <!-- <div class="ui-tabs-nav ui-tabs-nav-left">
                <template v-for="(h, hindex) in headers">
                    <a v-bind="h.bind" v-html="h.title" @click="setActive(hindex)"></a>
                </template>
            </div> -->

            <ul :class="`nav nav-tabs ui-tabs-tab ui-tabs-tab-left ui-tabs-halign-${props.halign}`" v-if="props.position=='left'">
                <template v-for="(h, hindex) in headers">
                    <a class="nav-link" href="javascript:void(0);" v-html="h.title"
                        :class="{active:hindex==props.value}"
                        @click="setActive(hindex)"
                    ></a>
                </template>
            </ul>
        </div>

        <div class="col">
            <div class="ui-tabs-content p-2" :class="`ui-tabs-content-${props.position}`">
                <slot name="content"></slot>
            </div>
        </div>

        <div class="col-2" v-if="props.position=='right'">
            <!-- <div class="ui-tabs-nav ui-tabs-nav-right">
                <template v-for="(h, hindex) in headers">
                    <a v-bind="h.bind" v-html="h.title" @click="setActive(hindex)"></a>
                </template>
            </div> -->

            <ul :class="`nav nav-tabs ui-tabs-tab ui-tabs-tab-right ui-tabs-halign-${props.halign}`" v-if="props.position=='right'">
                <template v-for="(h, hindex) in headers">
                    <a class="nav-link" href="javascript:void(0);" v-html="h.title"
                        :class="{active:hindex==props.value}"
                        @click="setActive(hindex)"
                    ></a>
                </template>
            </ul>
        </div>
    </div>

    <!-- <div class="ui-tabs-nav ui-tabs-nav-bottom" v-if="props.position=='bottom'">
        <template v-for="(h, hindex) in headers">
            <a v-bind="h.bind" v-html="h.title" @click="setActive(hindex)"></a>
        </template>
    </div> -->

    <ul :class="`nav nav-tabs ui-tabs-tab ui-tabs-tab-bottom ui-tabs-halign-${props.halign}`" v-if="props.position=='bottom'">
        <template v-for="(h, hindex) in headers">
            <a class="nav-link" href="javascript:void(0);" v-html="h.title"
                :class="{active:hindex==props.value}"
                @click="setActive(hindex)"
            ></a>
        </template>
    </ul>
</div></template>

<style>
.ui-tabs-tab {}
.ui-tabs-tab a {}

.ui-tabs-tab-top {}
.ui-tabs-tab-top.ui-tabs-halign-right {justify-content:flex-end;}

.ui-tabs-tab-right {border-bottom:none; border-left:solid 1px #dee2e6;}
.ui-tabs-tab-right.ui-tabs-halign-right a {text-align:right;}
.ui-tabs-tab-right a {display:block!important; width:100%; border-radius:0px 3px 3px 0px !important;}
.ui-tabs-tab-right a.active, .ui-tabs-tab-right a:hover {border-color:#dee2e6 #dee2e6 #dee2e6 transparent !important;}

.ui-tabs-tab-bottom {border-bottom:none; border-top:solid 1px #dee2e6;}
.ui-tabs-tab-bottom.ui-tabs-halign-right {justify-content:flex-end;}
.ui-tabs-tab-bottom a {border-radius:0px 0px 3px 3px !important;}
.ui-tabs-tab-bottom a.active, .ui-tabs-tab-bottom a:hover {border-color:transparent #dee2e6 #dee2e6 #dee2e6 !important;}

.ui-tabs-tab-left {border-bottom:none; border-right:solid 1px #dee2e6;}
.ui-tabs-tab-left.ui-tabs-halign-right a {text-align:right;}
.ui-tabs-tab-left a {display:block!important; width:100%; border-radius:3px 3px 0px 0px !important;}
.ui-tabs-tab-left a.active, .ui-tabs-tab-left a:hover {border-color:#dee2e6 transparent #dee2e6 #dee2e6 !important;}

/* .ui-tabs {}
.ui-tabs .row-eq-height {display:-webkit-box; display:-webkit-flex; display:-ms-flexbox; display:flex;}

.ui-tabs-halign-left .ui-tabs-nav-right, .ui-tabs-halign-left .ui-tabs-nav-left {text-align:left;}
.ui-tabs-halign-right .ui-tabs-nav-right, .ui-tabs-halign-left .ui-tabs-nav-left {text-align:right;}
.ui-tabs-halign-right .ui-tabs-nav-top, .ui-tabs-halign-right .ui-tabs-nav-bottom {justify-content:flex-end;}

.ui-tabs-nav {position:relative; z-index:1;}

.ui-tabs-nav a {background:var(--light); color:var(--dark);}
.ui-tabs-nav a.active {background:#fff;}

.ui-tabs-nav-top {border-bottom:solid 1px transparent; margin:0px 0px -1px 0px; display:flex;}
.ui-tabs-nav-right {border-left:solid 1px transparent; margin:0px 0px 0px -1px; display:block;}
.ui-tabs-nav-bottom {border-top:solid 1px transparent; margin:-2px 0px 0px 0px; display:flex;}
.ui-tabs-nav-left {border-right:solid 1px transparent; margin:0px -1px 0px 0px; display:block;}

.ui-tabs-content {position:relative; z-index:2;}
.ui-tabs-content-top {}
.ui-tabs-content-right {}
.ui-tabs-content-bottom {}
.ui-tabs-content-left {} */
</style>

<script>
import Vue from 'vue';

export default {
  name: 'ui-tabs',

  props: {
    value: {default:0},
    position: {default:"top"},
    halign: {default:"left"},
  },

  watch: {
    $props: {deep:true, handler(value) {
      this.props = Object.assign({}, value);
      this.setActive(this.props.value);
    }},
  },

    methods: {
        getHeaders() {
            let headers = [];
            this.$el.querySelectorAll('.ui-tabs-content > *').forEach((item, index) => {
                headers.push({
                    title: item.dataset.title||`Item ${index}`,
                    bind: {
                        href: "javascript:void(0);",
                        class: {
                            'nav-link': true,
                            'active': index==this.props.value,
                        },
                    },
                });
            });
            return headers;
        },

        setActive(value) {
            this.props.value = value;
            this.$emit('input', value);

            this.headers = this.getHeaders();
            
            this.$el.querySelectorAll('.ui-tabs-content > *').forEach((item, index) => {
                item.style.display = this.props.value==index? 'block': 'none';
            });
        },
    },

  data() {
    return {
      headers: [],
      props: Object.assign({}, this.$props),
    };
  },

  mounted() {
    this.headers = this.getHeaders();
    this.setActive(this.props.value);
  },
}
</script>

<style>
.ui-tabs-content > * {}
</style>