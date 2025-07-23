<template><div class="ui-timeago" :title="props.value|datetime">
    <time-ago :datetime="validDatetime(props.value)" :refresh="props.refresh" :locale="props.locale" :long="props.long" v-if="props.value"></time-ago>
    <div v-else>&nbsp;</div>
</div></template>

<script>
import TimeAgo from 'vue2-timeago';

export default {
    components: {TimeAgo},

    props: {
        value: {default:0},
        locale: {default:'pt_BR'},
        refresh: {default:60},
        long: {default:true},
    },

    watch: {
		$props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    methods: {
        validDatetime(value) {
            return (value||'').replace('T', ' ');
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>