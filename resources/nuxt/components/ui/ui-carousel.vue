<template><div class="ui-carousel">
  <flicking ref="flicking" tag="div" class="ui-carousel-slider" @change="onChange" :options="compOptions">
      <slot name="sliders"></slot>
  </flicking>

  <!-- Arrows -->
  <slot name="arrows" :flicking="flicking" :value="props.value">
    <button type="button" @click="prev()" class="ui-carousel-arrows ui-carousel-arrows-prev">
      <i class="fa fa-fw fa-chevron-left"></i>
    </button>
    <button type="button" @click="next()" class="ui-carousel-arrows ui-carousel-arrows-next">
      <i class="fa fa-fw fa-chevron-right"></i>
    </button>
  </slot>

  <!-- Navigators -->
  <slot name="navigators" :flicking="flicking" :value="props.value">
    <div class="ui-carousel-navigators">
      <a v-for="i in itemsTotal" href="javascript:void(0);" class="ui-carousel-navigators-each" :class="{'ui-carousel-navigators-each-active':i-1==props.value}" @click="moveTo(i-1)"></a>
    </div>
  </slot>
</div></template>


<style scoped>
.ui-carousel {position:relative;}
.ui-carousel-slider {position:relative; z-index:1;}

.ui-carousel-arrows {position:absolute; top:0px; height:100%; z-index:2; background:none; border:none; outline:0!important; color:#ccc; font-size:25px; padding:0px;}
.ui-carousel-arrows-prev {left:0px;}
.ui-carousel-arrows-next {right:0px;}

.ui-carousel-navigators {text-align:center;}
.ui-carousel-navigators-each {display:inline-block; padding:7px; background:#ffffff99; border-radius:50%; margin:0px 5px;}
.ui-carousel-navigators-each-active {background:#ffffff;}
</style>


<script scoped>
import { Flicking } from "@egjs/vue-flicking";

export default {
  components: { Flicking },

  props: {
    value: {default:0},
    options: {default:()=>({})},
  },

  watch: {
    $props: {deep:true, handler(value) {
      this.props = Object.assign({}, value);
      this.flickingInit();
    }},
  },

  data() {
    return {
        props: Object.assign({}, this.$props),
        flicking: false,
        itemsTotal: 0,
    };
  },

  computed: {
    compOptions() {
      return Object.assign({
        circular: true,
      }, this.options);
    },
  },

  methods: {
    flickingInit() {
      setTimeout(() => {
        try {
          this.flicking = this.$refs.flicking;
          this.itemsTotal = this.$refs.flicking.getPanelCount();
          this.$refs.flicking.moveTo(this.$props.value);
        } catch(e) { console.error(e); }
      }, 100);
    },

    onChange(ev) {
      this.props.value = ev.index;
      this.$emit('input', this.props.value);
    },

    moveTo(index, duration) { this.$refs.flicking.moveTo(index, duration); },
    prev() { this.$refs.flicking.prev(); },
    next() { this.$refs.flicking.next(); },
  },

  mounted() {
    this.flickingInit();
  },
};
</script>
