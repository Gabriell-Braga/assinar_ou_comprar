<template><div class="ui-pagination" v-if="props.last_page>1">
    <div class="d-flex">
        <div class="pr-1">
            <a href="javascript:;" class="btn btn-sm ui-pagination-btn" :class="{'ui-pagination-btn-active':props.current_page==1}" @click="setPage(1)">
                <i class="fa fa-fw fa-angle-double-left"></i>
            </a>
        </div>

        <div class="pr-1">
            <a href="javascript:;" class="btn btn-sm ui-pagination-btn" :class="{'ui-pagination-btn-active':props.current_page==1}" @click="setPage('-')">
                <i class="fa fa-fw fa-angle-left"></i>
            </a>
        </div>

        <div class="text-center ui-pagination-scroll" @wheel="onWheel($event)">
            <a href="javascript:;" class="btn btn-sm ui-pagination-btn" :class="{'ui-pagination-btn-active':props.current_page==p}" @click="setPage(p)" v-for="p in props.last_page">
                {{ p }}
            </a>
        </div>
        
        <div class="pl-1">
            <a href="javascript:;" class="btn btn-sm ui-pagination-btn" :class="{'ui-pagination-btn-active':props.current_page==props.last_page}" @click="setPage('+')">
                <i class="fa fa-fw fa-angle-right"></i>
            </a>
        </div>

        <div class="pl-1">
            <a href="javascript:;" class="btn btn-sm ui-pagination-btn" :class="{'ui-pagination-btn-active':props.current_page==props.last_page}" @click="setPage(props.last_page)">
                <i class="fa fa-fw fa-angle-double-right"></i>
            </a>
        </div>
    </div>
</div></template>

<style>
.ui-pagination-btn {width:30px; height:30px; margin:0px 2px; padding:4px 0px 0px 0px; border-radius:50%;}
.ui-pagination-btn-active {background:#ddd;}

.ui-pagination-scroll {white-space:nowrap; overflow:auto; padding-bottom:3px;}
.ui-pagination-scroll::-webkit-scrollbar-track {}
.ui-pagination-scroll::-webkit-scrollbar {height:4px;}
.ui-pagination-scroll::-webkit-scrollbar-thumb {background:#ccc; cursor:pointer;}
</style>

<script>
export default {
    props: {
        current_page: {default:1},
        from: {default:0},
        to: {default:0},
        last_page: {default:0},
        per_page: {default:0},
        total: {default:0},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        setPage(page) {
            if (page=='+') { page = this.props.current_page+1; }
            else if (page=='-') { page = this.props.current_page-1; }

            this.props.current_page = page;
            this.$emit('change', this.props);
        },

        onWheel(ev) {
            ev.preventDefault();
            let targetScroll = ev.target.classList.contains('ui-pagination-scroll')? ev.target: ev.target.parentNode;
            targetScroll.scrollLeft += ev.deltaY/2;
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>