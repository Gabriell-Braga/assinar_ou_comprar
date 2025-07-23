<template><div class="ui-slider">
    <slot name="sliders"></slot>
</div></template>

<script>
export default {
    name: "ui-slider",

    props: {
        value: {default:0},
    },

    data() {
        return {
            props: Object.assign({}, this.$props),
        };
    },
};</script>


<style>
.ui-slider {position:relative; height:10px; outline:solid 1px red;}
.ui-slider input {
    position: absolute;
    top: 0px;
    left: 0px;
    -webkit-appearance: none;  /* Override default CSS styles */
    appearance: none;
    width: 100%; /* Full-width */
    height: 50%;
    background: none;
    outline: none; /* Remove outline */
    -webkit-transition: .2s; /* 0.2 seconds transition on hover */
    background: red;
}

.ui-slider input::-webkit-slider-thumb {
    z-index: 9;
    -webkit-appearance: none; /* Override default look */
    appearance: none;
    width: 25px; /* Set a specific slider handle width */
    height: 25px; /* Slider handle height */
    background: green; /* Green background */
    cursor: pointer; /* Cursor on hover */
    border-radius: 50%;
}

.ui-slider input::-moz-range-thumb:hover {
    background: red;
}

.ui-slider input::-moz-range-thumb {
  width: 25px; /* Set a specific slider handle width */
  height: 0%;
  background: red; /* Green background */
  cursor: pointer; /* Cursor on hover */
}
</style>