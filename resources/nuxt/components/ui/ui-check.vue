<template><div class="ui-check">
  <label class="input-group">
    <div class="input-group-prepend"><div class="input-group-text bg-transparent border-0 p-1">
      <i v-bind="compIconBind"></i>
      <input v-bind="compInputBind" v-model="props.value" :true-value="props.trueValue" :false-value="props.falseValue" @input="emit()">
    </div></div>
    <div class="form-control bg-transparent border-0">
      <slot name="default"></slot>
    </div>
  </label>
</div></template>

<style>
.ui-check {user-select:none; cursor:pointer!important;}
.ui-check-input input {display:none;}
.ui-check-input input + div {border:solid 5px red;}
</style>

<script>
export default {
  props: {
    value: {default:''},
    type: {default:'checkbox'},
    trueValue: {default:'1'},
    falseValue: {default:''},
    dark: {default:true},
    colorClass: {default:'dark'},
  },

  watch: {
    $props: {deep:true, handler(value) {
      this.props = Object.assign({}, value);
    }},
  },

  methods: {
    emit() {
      setTimeout(() => {
        this.$emit('input', this.props.value);
      }, 10);
    },
  },

  computed: {
    compIconBind() {
      let bind = {
        class: [],
        style: {fontSize:'25px'},
      };

      if (this.props.type=='checkbox') {
        bind.class.push((this.props.dark? 'fas': 'far'));
        bind.class.push((this.props.value==this.props.trueValue? 'fa-check-square': 'fa-square'));
      }

      else if (this.props.type=='radio') {
        bind.class.push((this.props.dark? 'fas': 'far'));
        bind.class.push((this.props.value==this.props.trueValue? 'fa-dot-circle': 'fa-circle'));
      }

      else if (this.props.type=='toggle') {
        bind.class.push('fas');
        bind.class.push((this.props.value==this.props.trueValue? 'fa-toggle-on': 'fa-toggle-off'));
        bind.style.fontSize = '30px';
      }

      bind.class.push(`text-${this.props.colorClass}`);

      return bind;
    },

    compInputBind() {
      let bind = {
        class: ['d-none'],
        type: 'checkbox',
        // trueValue: this.props.trueValue,
        // falseValue: this.props.falseValue,
      };

      // if (this.props.type=='toggle') {
      //   bind.type = 'checkbox';
      // }

      return bind;
    },
  },

  data() {
    let data = {};
    data.props = Object.assign({}, this.$props);
    return data;
  },
};
</script>
