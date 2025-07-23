<template><div class="ui-code bg-dark">
  <codemirror v-model="props.value" :options="compOptions"></codemirror>
</div></template>

<style>
.ui-code .CodeMirror-cursor {height:16px!important;}
.ui-code .CodeMirror-line, .ui-code .CodeMirror-line * {font:16px monospace!important; line-height:16px!important;}
.ui-code .CodeMirror-code > * {}
.ui-code .CodeMirror {height:auto;}

.CodeMirror-hscrollbar::-webkit-scrollbar {width:8px; height:8px;}
.CodeMirror-hscrollbar::-webkit-scrollbar-track {background: transparent;}
.CodeMirror-hscrollbar::-webkit-scrollbar-thumb {background: #999; border-radius: 6px;}
</style>


<script>
// ambiance
// ayu-dark
// ayu-mirage
// bespin
// gruvbox-dark
// hopscotch
// icecoder
// material-darker
// material-ocean
// material
// monokai
// nord
// oceanic-next
// railscasts
// shadowfox
// solarized
// tomorrow-night-bright
// tomorrow-night-eighties
// twilight
// vibrant-ink

import codemirror from 'vue-codemirror';
import 'codemirror/lib/codemirror.css';
import 'codemirror/theme/bespin.css';

import 'codemirror/mode/php/php.js';
import 'codemirror/mode/xml/xml.js';
import 'codemirror/mode/javascript/javascript.js';
import 'codemirror/mode/css/css.js';
import 'codemirror/mode/htmlmixed/htmlmixed.js';

export default {
  components: {
    codemirror: codemirror.codemirror,
  },

  props: {
    value: {default:''},
    mode: {default:'htmlmixed'},
  },

  watch: {
    $props: {deep:true, handler(value) {
      this.props = Object.assign({}, value);
    }},
  },

  computed: {
    compOptions() {
      let mode = this.props.mode;
      if (mode=='html') { mode = 'htmlmixed'; }

      return {
        tabSize: 4,
        mode: mode,
        theme: 'bespin',
        lineNumbers: true,
        lineWrapping: false,
        line: true,
      };
    },
  },

  data() {
    let data = {};
    data.props = Object.assign({}, this.$props);
    return data;
  },

  methods: {
    emit() {
      this.$emit('input', this.props.value);
    },
  },

  mounted() {
    
  },
};
</script>
