<template><div class="ui-tooltip">
  <i class="fas fa-question-circle"></i>
  <div class="ui-tooltip-arrow"></div>

  <div class="ui-tooltip-relative">
    <div class="ui-tooltip-content ui-tooltip-bg" :style="`width:${props.tooltipWidth};`">
      <slot name="default"></slot>
    </div>
  </div>

  <div class="ui-tooltip-mobile ui-tooltip-bg">
    <slot name="default"></slot>
  </div>
</div></template>

<script>
export default {
  name: 'ui-tooltip',

  props: {
    position: {default:'top'}, // top | right | bottom | left
    tooltipWidth: {default:'250px'},
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
}
</script>

<style>
.ui-tooltip {display:inline-block;}
.ui-tooltip * {transition: all 300ms ease; color:#fff;}
.ui-tooltip i {cursor:pointer; color:#3E474F;}
.ui-tooltip-bg {background: #3E474F; padding:10px; color:#fff; border-radius:5px;}
.ui-tooltip-relative {position:relative;}

.ui-tooltip-content {position:absolute; left:15px; bottom:10px; max-height:300px; overflow:auto; visibility:hidden; opacity:0; z-index:9; transform:translate(-50%, -25px);}
.ui-tooltip-content, .ui-tooltip-content * {font-weight:normal!important; font-size:15px; line-height:18px!important;}
.ui-tooltip:hover .ui-tooltip-content,
.ui-tooltip:focus .ui-tooltip-content,
.ui-tooltip:active .ui-tooltip-content {visibility:visible; opacity:1;}

.ui-tooltip-mobile {position:absolute; left:10px; right:10px; margin-top:10px; visibility:hidden; opacity:0; z-index:9;}

.ui-tooltip-arrow {
  position: absolute;
  margin: -30px 0px 0px -5px;
  border-style: solid;
  border-width: 1em 0.75em 0 0.75em;
  border-color: #3E474F transparent transparent transparent;
  content: "";
  visibility: hidden;
  opacity: 0;
  transform:  scale(.6) translateY(-90%);
}

.ui-tooltip:hover .ui-tooltip-arrow,
.ui-tooltip:focus .ui-tooltip-arrow,
.ui-tooltip:active .ui-tooltip-arrow {visibility:visible; opacity:1;}

@media (max-width: 768px) {
  .ui-tooltip-content {display:none;}
  .ui-tooltip:hover .ui-tooltip-mobile,
  .ui-tooltip:focus .ui-tooltip-mobile,
  .ui-tooltip:active .ui-tooltip-mobile {visibility:visible; opacity:1;}
  .ui-tooltip-arrow {margin: 0px 0px 0px -4px; transform:rotate(180deg)}
}
</style>