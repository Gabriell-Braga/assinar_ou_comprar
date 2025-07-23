<!-- https://grapesjs.com/docs/getting-started.html#import-the-library -->

<template><div class="ui-grape">
    <div class="row no-gutters">
        <div class="col-8">
            <div ref="grapejs"></div>
        </div>
        <div class="col-4">
            <div ref="grapejsBlocks"></div>
        </div>
    </div>
    <pre>props: {{ props }}</pre>
</div></template>


<style>
.ui-grape .gjs-cv-canvas {top: 0; width: 100%; height: 100%;}
.ui-grape .gjs-block {width: auto; height: auto; min-height: auto;}
</style>


<script>
// import Vue from 'vue';

import 'grapesjs/dist/css/grapes.min.css';
import grapesjs from 'grapesjs';

export default {
    props: {
        value: {default:()=>({})},
    },

    data() {
        return {
            grapejs: false,
            props: Object.assign({}, this.$props),
        };
    },

    methods: {
        grapejsInit() {
            let editor = grapesjs.init({
                // Indicate where to init the editor. You can also pass an HTMLElement
                container: this.$refs.grapejs,
                // Get the content for the canvas directly from the element
                // As an alternative we could use: `components: '<h1>Hello World Component!</h1>'`,
                fromElement: true,
                // Size of the editor
                height: '50vh',
                width: 'auto',
                // Disable the storage manager for the moment
                storageManager: false,
                // Avoid any default panel
                panels: { defaults: [] },


                blockManager: {
                    appendTo: this.$refs.grapejsBlocks,
                    blocks: [
                        {
                            id: 'section', // id is mandatory
                            label: `<div>Section</div>`, // You can use HTML/SVG inside labels
                            attributes: { class:'gjs-block-section' },
                            content: `<section>
                            <h1>This is a simple title</h1>
                            <div>This is just a Lorem text: Lorem ipsum dolor sit amet</div>
                            </section>`,
                        },
                        {
                            id: 'text',
                            label: 'Text',
                            content: '<div data-gjs-type="text">Insert your text here</div>',
                        },
                        {
                            id: 'image',
                            label: 'Image',
                            // Select the component once it's dropped
                            select: true,
                            // You can pass components as a JSON instead of a simple HTML string,
                            // in this case we also use a defined component type `image`
                            content: { type: 'image' },
                            // This triggers `active` event on dropped components and the `image`
                            // reacts by opening the AssetManager
                            activate: true,
                        },
                        {
                            id: 'bs4-card',
                            label: 'Card',
                            content: `<div class="card">
                                <div class="card-header">Header</div>
                                <div class="card-body">Body</div>
                            </div>`,
                        },
                    ],
                },
            });

            editor.on('change', () => {
                this.props.value = JSON.parse(JSON.stringify(editor.getComponents()));
            });

            this.grapesjs = editor;
        },
    },

    mounted() {
        this.grapejsInit();
    },
};</script>