<template><div class="ui-select" :class="{'ui-select-dropdown':dropdown, 'ui-select-focused':focused}">
  <div class="bg-white shadow-sm p-2" v-html="selectedLabel" v-if="props.dropdown"></div>

  <div class="ui-select-dropdown-content bg-white" :class="{'shadow':props.dropdown}" :style="`overflow:auto; max-height:${maxHeight};`">
    
    <div class="input-group form-control p-0 m-0 bg-light" v-if="props.showFilter">
      <input type="text" class="form-control bg-transparent border-0" placeholder="Filtrar" v-model="props.filter" @keyup="filterItems()">
      <div class="input-group-append"><div class="input-group-btn">
        <button type="button" class="btn bg-transparent">
          <i class="fas fa-search"></i>
        </button>
      </div></div>
    </div>

    <div class="ui-select-items list-group list-group-flush m-0">
      <slot></slot>
    </div>
  </div>
</div></template>


<style>
.ui-select * {transition: all 300ms ease;}
.ui-select {position:relative; cursor:pointer;}
.ui-select > .ui-select-items {position:static; top:100%; left:0px; width:100%;}
.ui-select-item {opacity:1;}
.ui-select-item-hide {opacity:0; height:0px; margin:0px; padding:0px; transform:scaleY(0);}

.ui-select.ui-select-dropdown .ui-select-dropdown-content {position:absolute; top:100%; left:0px; width:100%; z-index:-9; max-height:300px !important;}
.ui-select.ui-select-dropdown.ui-select-focused .ui-select-dropdown-content {z-index:9;}
</style>


<script>
import Vue from 'vue';

Vue.component('ui-select-item', {
  props: {
    value: [Boolean, Number, String, Object, Array],
  },

  methods: {
    select() {
      if (this.$parent.props.multiple) {
        let index = this.$parent.props.value.indexOf(this.value);
        index>=0? this.$parent.props.value.splice(index, 1): this.$parent.props.value.push(this.value);
      }

      else {
        this.$parent.props.value = this.$parent.props.value==this.value? false: this.value;
      }

      setTimeout(() => {
        this.$parent.setValue(this.$parent.props.value);
        this.$parent.$forceUpdate();
      }, 10);
    },

    selected() {
      if (this.$parent.props.multiple) {
        return this.$parent.props.value.indexOf(this.value)>=0;
      }

      return this.$parent.props.value==this.value;
    },
  },

  template: `<div class="ui-select-item list-group-item" :class="{'ui-select-item-selected':selected(value)}" @click="select()">
    <div class="d-flex align-items-center">
      <div class="flex-grow-1 ui-select-item-content" ref="content">
        <slot></slot>
      </div>

      <div class="text-right" style="width:25px;">
        <i class="fas" :class="{'fa-check':selected(value)}"></i>
      </div>
    </div>
  </div>`,
});

export default {
  props: {
      value: [Boolean, Number, String, Object, Array],
      multiple: {default:false},
      maxHeight: {default:'none'},
      dropdown: {default:true},
      empty: {default:'Nenhum item selecionado'},
      filter: {default:''},
      showFilter: {default:true},
  },

  watch: {
      $props: {deep:true, handler(value) {
          this.props = Object.assign({}, value);
          this.setSelectedLabel();
      }},
  },

  data() {
    let data = {};
    data.focused = false;
    data.selectedLabel = this.$props.empty;
    data.props = Object.assign({}, this.$props);
    
    if (data.props.multiple) {
      if (! Array.isArray(data.props.value)) {
        data.props.value = [data.props.value];
      }
    }

    else {
      if (Array.isArray(data.props.value)) {
        data.props.value = data.props.value[0] || false;
      }
    }

    return data;
  },

  methods: {
    setValue(value) {
      this.props.value = value;
      this.$emit('input', this.props.value);
    },

    filterItems() {
      this.$el.querySelectorAll('.ui-select-items > .list-group-item').forEach(item => {
        item.classList.remove('ui-select-item-hide');

        if (this.props.filter && !item.innerText.toLowerCase().includes(this.props.filter.toLowerCase())) {
          item.classList.add('ui-select-item-hide');
        }
      });
    },

    setSelectedLabel() {
      setTimeout(() => {
        if (! this.$el) {
          this.selectedLabel = this.props.empty;
          return;
        }

        let selecteds = this.$el.querySelectorAll('.ui-select-item-selected .ui-select-item-content');

        if (selecteds.length==1) {
          this.selectedLabel = selecteds[0].innerHTML;
          return;
        }
        else if (selecteds.length>1) {
          this.selectedLabel = `<div class="d-flex align-items-center">
            <div class="flex-grow-1">${selecteds[0].innerHTML}</div>
            <div>+${selecteds.length-1} ${selecteds.length-1==1? 'item': 'itens'}</div>
          </div>`;
          return;
        }
        
        this.selectedLabel = this.props.empty;
      }, 10);
    },

    documentClickHandle(ev) {
      this.focused = this.$el.contains(ev.target);
    },
  },

  mounted() {
    this.filterItems();
    this.setSelectedLabel();
    document.addEventListener('click', this.documentClickHandle);
  },

  beforeDestroy() {
    document.removeEventListener('click', this.documentClickHandle);
  },
}
</script>