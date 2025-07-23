<template><div class="ui-layout">
    <div v-for="elem1 in props.value.sections" :key="elem1.id">
        <div v-for="elem2 in elem1.items" :key="elem1.id" class="border" :class="{container:!elem1.full}">
            <div class="row" :class="{'no-gutters':!elem2.gutters}">
                <div v-for="elem3 in elem2.items" :class="[elem3.xs, elem3.sm, elem3.md, elem3.lg]">
                    <div v-for="elem4 in elem3.items">
                        <component :is="elem4.component" v-bind="elem4.componentBind"></component>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <pre>$data: {{ $data }}</pre>
</div></template>

<script>
export default {
    props: {
        value: {default:()=>({})},
    },

    watch: {
		$props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
            components: [
                {
                    id: "content",
                    name: "Content",
                    component: require('./ui-layout/content').default,
                },
            ],
        };
    },

    methods: {
        uuid(prefix='') { return prefix+([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c => (c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))).toString(16)); },

        sectionAdd(merge={}) {
            let section = Object.assign({
                type: "section",
                id: this.uuid('section-'),
                classes: "",
                tag: "section",
                items: [],
            }, merge);
            
            this.props.value.sections.push(section);
            return section;
        },

        containerAdd(section, merge={}) {
            let container = Object.assign({
                type: "container",
                id: this.uuid('container-'),
                classes: "",
                full: false,
                gutters: true,
                items: [],
            }, merge);

            section.items.push(container);
            return container;
        },

        colAdd(container, merge={}) {
            let col = Object.assign({
                type: "column",
                id: this.uuid('col-'),
                classes: "",
                xs: "col-12",
                sm: false,
                md: false,
                lg: false,
                items: [],
            }, merge);

            container.items.push(col);
            return col;
        },

        componentAdd(col, component, merge={}) {
            console.error('component', component);

            let comp = Object.assign({
                type: "component",
                id: this.uuid('component-'),
                classes: "",
                component: component,
                componentBind: {},
            }, merge);

            col.items.push(comp);
            return comp;
        },

        defaultValue(merge={}) {
            return Object.assign({
                sections: [],
            }, merge);
        },
    },

    mounted() {
        this.props.value = this.defaultValue(this.props.value);

        let section = this.sectionAdd();
        let container = this.containerAdd(section);
        let col1 = this.colAdd(container, {sm:"col-sm-6"});
        let col2 = this.colAdd(container, {sm:"col-sm-6"});
        let component = this.componentAdd(col2, this.components[0].component);
    },
};</script>