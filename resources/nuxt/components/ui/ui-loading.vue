<template><div v-if="loading">
    <div v-if="type=='icon'" class="d-flex align-items-center">
        <i class="fa-spin fa-2x mr-2" :class="`${icon} text-${colorClass}`"></i>
        <div class="flex-grow-1 pl-2" v-if="label" v-html="label"></div>
    </div>

    <div v-else-if="type=='spinner'" class="d-flex align-items-center">
        <div :class="`spinner-border text-${colorClass}`" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="flex-grow-1 pl-3" v-if="label" v-html="label"></div>
    </div>

    <div v-else-if="type=='progress'">
        <div class="progress">
            <div :class="`progress-bar bg-${colorClass} progress-bar-striped progress-bar-animated`" role="progressbar"
                :aria-valuenow="percent" aria-valuemin="0" aria-valuemax="100" :style="{width:percent+'%'}" v-html="label"
            ></div>
        </div>
    </div>

    <div v-else><slot>Carregando...</slot></div>
</div></template>

<script>
export default {
    props: {
        type: {default: 'icon'},
        icon: {default: 'fas fa-sync'},
        percent: {default: 0},
        label: {default: ''},
        loading: {default: true},
        colorClass: {default: 'primary'},
    },
}
</script>