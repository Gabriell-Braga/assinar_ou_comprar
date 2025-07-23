<template><div>
    <div class="progress" style="height:5px;">
        <div class="progress-bar progress-bar-striped" :class="`bg-${meter.class}`" :style="`width:${meter.score}%;`"></div>
    </div>
</div></template>

<script>export default {
    props: {
        value: {default: false},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    computed: {
        meter() {
            let pass = this.props.value;
            let score = 0;
            let className = 'danger';

            if (pass) {
                // award every unique letter until 5 repetitions
                let letters = new Object();
                for (let i=0; i<pass.length; i++) {
                    letters[pass[i]] = (letters[pass[i]] || 0) + 1;
                    score += 5.0 / letters[pass[i]];
                }
    
                // bonus points for mixing it up
                let variations = {
                    digits: /\d/.test(pass),
                    lower: /[a-z]/.test(pass),
                    upper: /[A-Z]/.test(pass),
                    nonWords: /\W/.test(pass),
                }
    
                let variationCount = 0;
                for (let check in variations) {
                    variationCount += (variations[check] == true) ? 1 : 0;
                }
                score += (variationCount - 1) * 10;
    
                score = parseInt(score);
                if (score>=70) { className='success'; }
                else if (score>=40) { className='primary'; }
            }


            return {
                score: score,
                class: className,
            };
        },
    },

    methods: {
        emit() {
            this.$emit('input', this.props.value);
            this.$emit('value', this.props.value);
            this.$emit('change', this.props.value);
        },
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>