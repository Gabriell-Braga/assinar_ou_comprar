<template><div class="ui-actions">
    <br><br>
    <div class="ui-actions-fixed bg-white text-center text-md-right">
        <div class="d-flex d-md-block p-md-1">
            <slot></slot>
        </div>
    </div>
</div></template>

<style>
.ui-actions-fixed {position:fixed; bottom:0px; left:0px; width:100%; z-index:999; box-shadow:0 -3px 3px -2px rgb(0 0 0 / 10%) !important;}
.ui-actions .d-flex > * {flex-grow:1;}
.ui-actions .btn {border-radius:0px !important;}
</style>