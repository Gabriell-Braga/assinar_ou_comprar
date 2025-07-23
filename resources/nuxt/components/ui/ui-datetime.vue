<template><div class="ui-datetime" :class="{'ui-datetime-flatpickr-show':flatpickrShow}" style="position:relative;">
    <input type="text" class="form-control" v-model="valueFake"
        ref="input" autocomplete="chrome-off"
        v-mask="['##/##/####', '##/##/#### - ##:##:##']"
        @keyup="formattedToDate($event); emit();"
        @focus="flatpickrShowTest()"
        @blur="flatpickrShowTest()"
    >
    <div class="ui-datetime-flatpickr" ref="flatpickr"></div>
</div></template>

<style>
.ui-datetime .ui-datetime-flatpickr {}
.ui-datetime .flatpickr-calendar {visibility:hidden; opacity:0; transition: all 200ms ease; position:absolute; top:110%; left:0px; min-width:310px; z-index:9;}
.ui-datetime .flatpickr-calendar * {user-select: none;}
.ui-datetime.ui-datetime-flatpickr-show .flatpickr-calendar {visibility:visible; opacity:1;}
</style>

<script>
import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.css';

import moment from 'moment';

export default {
    props: {
        value: {default: ''},
        time: {default: true},
    },

    computed: {
        compConfig() {
            let data = {};

            if (this.props.time) {
                data.inline = true;
                data.enableTime = true;
                data.time_24hr = true;
            }

            data.onChange = (selectedDates, dateStr, pickr) => {
                this.props.value = pickr.formatDate(selectedDates[0], 'Y-m-d H:i:S');
                this.valueFake = pickr.formatDate(selectedDates[0], 'd/m/Y H:i:S');
                this.flatpickrShowTest();
                this.$refs.input.focus();
                this.emit();
            };

            data.locale = {
                weekdays: {
                    shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    longhand: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
                },

                months: {
                    shorthand: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    longhand: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                },

                rangeSeparator: " até ",
                time_24hr: true,
            };

            return data;
        },
    },

    data() {
        return {
            valueFake: false,
            flatpickr: false,
            flatpickrShow: false,
            props: Object.assign({}, this.$props),
        };
    },

    methods: {
        emit() {
            this.$emit('input', this.props.value);
        },

        formattedToDate(ev) {
            let e = this.valueFake.split(/[^0-9]/).filter(n => !!n);

            if (e[2] && e[2].length==4) {
                this.props.value = [
                    e[2],
                    (e[1] || '01'),
                    (e[0] || '01'),
                ].join('-')
                + ' '+ [
                    (e[3] || '00'),
                    (e[4] || '00'),
                    (e[5] || '00'),
                ].join(':');

                this.flatpickr.setDate(this.props.value);
            }
        },

        flatpickrShowTest() {
            setTimeout(() => {
                this.flatpickrShow = this.$el.contains(document.activeElement);
            }, 100);
        },
    },

    mounted() {
        this.flatpickr = flatpickr(this.$refs.flatpickr, this.compConfig);
        this.valueFake = moment(this.props.value).format('DD/MM/YYYY - HH:mm');
    },
};</script>