<template>
    <div class="ui-dropdown" style="position:relative; z-index:9;">
        <transition
            name="ui-dropdown-animation"
            enter-active-class="animate__animated animate__fadeIn"
            leave-active-class="animate__animated animate__fadeOut"
        >
            <div style="position:absolute; top:0px; left:0px; width:100%; z-index:9!important; animation-duration:300ms;" v-if="props.shown">
                <slot name="content">
                    <div class="bg-white shadow-sm">
                        content
                    </div>
                </slot>
            </div>
        </transition>
    </div>
</template>

<script>export default {
    props: {
        value: {default: false},
        shown: {default: false},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    data() {
        let data = {};
        data.target = false;
        data.props = Object.assign({}, this.$props);

        data.events = [
            {type:'click', handler: (ev) => {
                this.hide();
                
                let closest = this.$el.closest('.ui-dropdown');
                if (closest && closest.contains(ev.target)) {
                    this.show();
                }

                else if (this.target && (ev.target==this.target || this.target.contains(ev.target))) {
                    this.show();
                }
            }},
        ];

        return data;
    },

    methods: {
        toggle(ev=null) {
            this.props.shown? this.hide(ev): this.show(ev);
        },

        show(ev=null) {
            if (ev) { this.target = ev.target||false; }
            this.props.shown = true;
            this.$emit('shown', this.props.shown);
        },
        
        hide(ev=null) {
            if (ev) { this.target = ev.target||false; }
            this.props.shown = false;
            this.$emit('shown', this.props.shown);
        },
    },

    mounted() {
        this.events.forEach(ev => {
            window.addEventListener(ev.type, ev.handler);
        });
    },

    beforeDestroy() {
        this.events.forEach(ev => {
            window.removeEventListener(ev.type, ev.handler);
        });
    },
};</script>