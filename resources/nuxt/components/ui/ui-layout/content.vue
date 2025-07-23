<template><div>
    <h1 v-html="props.title"></h1>
    <div v-html="props.text"></div>
</div></template>

<script>export default {
    props: {
        title: {default:'Default title'},
        text: {default:'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim accusamus exercitationem explicabo eius totam at tempora earum, veritatis, architecto, voluptates fugit esse? Doloremque temporibus molestiae fugiat porro obcaecati rerum dignissimos.'},
    },

    edit: {
        title: {
            component: 'input',
            componentBind: {type:'text', class:'form-control'},
            value: 'Aaa',
        },
        text: {
            component: 'ui-content',
            componentBind: {},
            value: 'Aaa',
        },
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
};</script>