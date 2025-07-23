<template><div class="ui-laravel-pagination">
    <ul class="pagination m-0">
        <li class="page-item" v-for="(l, i) in compLinks" :key="i" :class="{active:l.active}">
            <a href="javascript:;" class="page-link" v-html="l.label" @click="emit(l)"></a>
        </li>
    </ul>
</div></template>

<script>export default {
    name: 'ui-laravel-pagination',

    props: {
        value: {default:''},
        range: {default:3},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        emit(link) {
            this.props.value.current_page = link.page;
            this.$emit('change', link);
        },
    },

    computed: {
        compLinks() {
            let pag=this.props.value, links=[];
            let pages = (pag.total&&pag.per_page)? Math.ceil(parseInt(pag.total)/parseInt(pag.per_page)): 0;
            if (pages<=1) return [];

            let min = Math.max(1, (pag.current_page||0)-this.range);
            let max = Math.min(pages, min+(this.range*2));

            links.push({page:1, active:1==pag.current_page, label:'<i class="fas fa-chevron-left"></i>'});

            for(let p=min; p<=max; p++) {
                links.push({page:p, active:p==pag.current_page, label:p});
            }

            links.push({page:pages, active:pages==pag.current_page, label:'<i class="fas fa-chevron-right"></i>'});
            return links;
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>
