
<template>
    <div class="ui-playground">
        <div class="row no-gutters bg-white">
            <div class="col-6">
                <ui-code v-model="code"></ui-code>
            </div>
            <div class="col-6" style="position:relative;">
                <iframe src="" style="border:none; width:100%; height:100%;"></iframe>
            </div>
        </div>
    </div>
</template>


<style>
.ui-playground {}
</style>


<script>
import Vue from 'vue';

export default {
  props: {
      value: {default:false},
  },

  watch: {
      $props: {deep:true, handler(value) {
          this.props = Object.assign({}, value);
      }},
  },

  data() {
      return {
        props: Object.assign({}, this.$props),
        // https://cdn.jsdelivr.net/npm/vue/dist/vue.js
        code: `<template>
  <div>
    <features :features="features"></features>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        features: [
          'Vue Single File Component support',
          'Scoped style',
          'UMD and CommonJS build',
          'Define JavaScript scope'
        ]
      }
    }
  }<\/script>`,
      };
  },
}
</script>