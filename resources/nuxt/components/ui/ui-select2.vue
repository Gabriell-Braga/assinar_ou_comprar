
<template>
    <div class="ui-select" style="position:relative;">

        <div class="form-control" style="height:auto;" ref="formControl" @click="$refs.dropdown.show($event)">

          <!-- if multiple -->
          <div v-if="props.multiple">
            
            <!-- if empty -->
            <div v-if="props.value.length==0">
              <slot name="empty">{{ props.placeholder }}</slot>
            </div>

            <!-- if populated -->
            <div v-else>
              <div class="d-flex align-items-center">
                <div class="flex-grow-1" style="overflow:hidden; white-space:nowrap;">
                  <slot name="selected" v-for="v in props.value" :option="v"></slot>
                </div>
                <div class="pl-2" v-if="props.value.length>1">{{ props.value.length }}</div>
              </div>
            </div>
          </div>

          <!-- if simple -->
          <div v-else>
            
            <!-- if empty -->
            <div v-if="!props.value">
              <slot name="empty">{{ props.placeholder }}</slot>
            </div>

            <!-- if populated -->
            <div v-else>
              <slot name="selected" :option="props.value"></slot>
            </div>
          </div>
        </div>

        <ui-dropdown ref="dropdown">
            <template #content>
              <div class="bg-white shadow mt-1">
                <slot name="options" :select="select" :selected="selected"></slot>
              </div>
            </template>
        </ui-dropdown>
    </div>
</template>


<style>
.ui-select * {transition: all 300ms ease;}
.ui-select > .form-control {cursor:pointer;}
</style>


<script>
import Vue from 'vue';

Vue.component('ui-select-item', {
  template: `<div></div>`,
});

export default {
  props: {
      value: {default:false},
      data: {default:()=>([])},
      name: {default:'some-name'},
      placeholder: {default:'Selecione'},
      dropdown: {default:false},
      multiple: {default:false},
  },

  components: {
    uiSelectOption: {
      props: {
        value: {default:()=>({})},
      },
    },
  },

  watch: {
      $props: {deep:true, handler(value) {
          this.props = Object.assign({}, value);
      }},
  },

  data() {
      return {
        options: [],
        props: Object.assign({}, this.$props),
      };
  },

  computed: {
      compData() {
          return this.data.map(item => {
              return {
                  id: 'input-'+ (item.id || btoa(JSON.stringify(item))),
                  option: item,
              };
          });
      },
  },

  methods: {
      emit() {
          this.$emit('input', this.props.value);
      },

      setDropdown(value=null) {
        value = value===null? (!!this.props.dropdown): value;
        this.props.dropdown = value;
      },

      getOptions() {
        let items = [];

        this.$el.querySelectorAll('.ui-select-options>*').forEach(item => {
          item.classList.add('ui-select-option');
          items.push({
            html: item.innerHTML,
            text: item.innerText,
          });
        });

        return items;
      },

      select(option, ev=false) {
        if (this.props.multiple) {
          this.props.value = Array.isArray(this.props.value)? this.props.value: [];
          let index = this.props.value.indexOf(option);
          index>=0? this.props.value.splice(index, 1): this.props.value.push(option);
          this.emit();
          return;
        }

        this.props.value = (this.props.value==option)? false: option;
        this.emit();
      },

      selected(option) {
        if (this.props.multiple) {
          this.props.value = Array.isArray(this.props.value)? this.props.value: [];
          return this.props.value.indexOf(option)>=0;
        }

        this.props.value = Array.isArray(this.props.value)? false: this.props.value;
        return this.props.value==option;
      },
  },

  mounted() {
    this.options = this.getOptions();
    this.setDropdown();
  },
}
</script>