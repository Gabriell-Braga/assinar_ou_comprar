<template><ul :class="`ui-nav ui-nav-type-${props.type} ui-nav-level-${props.level} bg-white shadow-sm`">
    <li v-for="i in props.value" :class="{'ui-nav-item-active':props.active(i)}">
        <nuxt-link :to="i.to" class="p-2 btn text-left">
            <slot name="link">
                <span v-html="i.title" :item="i"></span>
            </slot>
        </nuxt-link>

        <ui-nav v-model="i.items" v-if="i.items && Array.isArray(i.items)" :level="props.level+1"></ui-nav>
    </li>
</ul></template>

<style>
.ui-nav * {transition: all 200ms ease;}
.ui-nav {list-style-type:none; padding:0px; margin:0px;}
.ui-nav > li {position:relative;}
.ui-nav > li > a {display:block;}
.ui-nav > li ul {visibility:hidden; opacity:0; position:absolute; width:100%; min-width:150px; z-index:2;}
.ui-nav > li:hover > ul {visibility:visible; opacity:1;}

.ui-nav-type-vertical > li ul {top:0; left:100%;}

.ui-nav-type-horizontal.ui-nav-level-0 {display:flex;}
.ui-nav-type-horizontal.ui-nav-level-0 > li > ul {top:100%; left:0;}
.ui-nav-type-horizontal.ui-nav-level-1 ul {top:0; left:100%;}

.ui-nav-item-active > a, .ui-nav a:hover {background:#eee;}
</style>

<script>
export default {
    components: {
        'ui-nav': () => import('@/components/ui/ui-nav.vue'),
    },

    props: {
        value: {default:()=>([])},
        type: {default:'horizontal'},
        level: {default:0},
        active: {default:Function},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},

        $route: {deep:true, handler(value) {
            this.$forceUpdate();
        }},
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
}
</script>