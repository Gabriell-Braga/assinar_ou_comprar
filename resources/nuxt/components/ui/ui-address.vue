<template><div class="ui-address" style="position:relative; z-index:1;">
    <div class="row">
        <div class="col-12 col-lg-8 mb-2">
            <div class="input-group form-control border-0 p-0">
                <div class="input-group-prepend"><div class="input-group-text border-0" style="width:100px;">
                    CEP
                </div></div>
                <input type="text" class="form-control" v-model="props.value.zipcode"
                    v-mask="'#####-###'" autocomplete="off-none"
                    @keyup.enter.prevent="osmSearch({q:props.value.zipcode}, false)">
                <div class="input-group-append"><div class="input-group-btn border-0">
                    <button type="button" class="btn btn-secondary rounded-0" @click="osmSearch({q:props.value.zipcode}, false)">
                        <i class="fa fa-fw fa-spin fa-spinner" v-if="loading"></i>
                        <i class="fa fa-fw fa-search" v-else></i>
                    </button>
                </div></div>
            </div>
        </div>

        <div class="col-12"></div>

        <div class="col-12 col-lg-8 mb-2">
            <div class="input-group form-control border-0 p-0" style="height:auto;">
                <div class="input-group-prepend"><div class="input-group-text border-0" style="width:100px;">
                    Endereço
                </div></div>
                <input type="text" class="form-control" v-model="props.value.route" placeholder="Endereço"
                    autocomplete="off-none" @focus="$refs.dropdown.show($event)"
                    @keyup="debounce(500, () => { osmSearch({q:props.value.route}, true) })">
            </div>

            <ui-dropdown ref="dropdown">
                <template #content>
                    <div class="bg-white shadow" style="max-height:300px; overflow:auto;">
                        <a href="javascript:;" v-for="r in searchResults" class="d-block p-2 text-dark" style="border-bottom:solid 1px #eee; text-decoration:none;" @click="selectPlace(r); $refs.dropdown.toggle();">
                            {{ r.address.road||'' }}, {{ r.address.suburb||r.address.neighbourhood }} - {{ r.address.city }}/{{ getEstadoFromCode(r.address.state) }} | {{ r.address.postcode }}
                        </a>
                    </div>
                </template>
            </ui-dropdown>
        </div>

        <div class="col-6 col-lg-4 mb-2">
            <div class="input-group form-control border-0 p-0">
                <div class="input-group-prepend"><div class="input-group-text border-0" style="width:100px;">
                    Número
                </div></div>
                <input type="text" class="form-control" v-model="props.value.number" @change="emit()">
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-2">
            <div class="input-group form-control border-0 p-0" title="Complemento">
                <div class="input-group-prepend"><div class="input-group-text border-0" style="width:100px;">
                    Comp.
                </div></div>
                <input type="text" class="form-control" v-model="props.value.complement" @change="emit()">
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-2">
            <div class="input-group form-control border-0 p-0" :title="'Bairro '+props.value.district">
                <div class="input-group-prepend"><div class="input-group-text border-0" style="width:100px;">
                    Bairro
                </div></div>
                <input type="text" class="form-control" v-model="props.value.district" @change="emit()">
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="input-group form-control border-0 p-0">
                <input type="text" class="form-control" v-model="props.value.city" @change="emit()" placeholder="Cidade">

                <select class="form-control" v-model="props.value.st" @change="emit()">
                    <option value="">Estado</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>
            </div>
        </div>
    </div>

    <l-map v-if="mapBindComp" v-bind="mapBindComp" class="mt-2" style="height:200px; overflow:hidden; z-index:1;">
        <l-tile-layer url="http://{s}.tile.osm.org/{z}/{x}/{y}.png"></l-tile-layer>
        <l-marker :lat-lng="mapBindComp.center" :draggable="true" @moveend="osmReverse({lat:$event.sourceTarget._latlng.lat, lon:$event.sourceTarget._latlng.lng}, false)"></l-marker>
    </l-map>
</div></template>

<script>
export default {
    props: {
        value: {default: () => ({})},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    computed: {
        formattedAddress() {
            let addr=this.props.value, parts=[];
            if (addr.route) parts.push(addr.route);
            if (addr.number) parts.push(`Nº ${addr.number}`);
            if (addr.complement) parts.push(addr.complement);
            if (addr.zipcode) parts.push(`CEP ${addr.zipcode}`);
            if (addr.district) parts.push(addr.district);
            if (addr.city) parts.push(addr.city);
            if (addr.state) parts.push(addr.state);
            return parts.join(', ');
        },

        mapBindComp() {
            if (this.props.value && this.props.value.lat && this.props.value.lng) {
                return {
                    zoom: 16,
                    center: [
                        parseFloat(this.props.value.lat),
                        parseFloat(this.props.value.lng),
                    ],
                };
            }
            return false;
        },
    },

    methods: {
        emit() {
            this.$emit('input', this.props.value);
            this.$emit('value', this.props.value);
            this.$emit('change', this.props.value);
        },

        debounce(timeout, callback) {
            if (this._debounceTimeout) clearTimeout(this._debounceTimeout);
            this._debounceTimeout = setTimeout(callback, timeout);
        },

        getEstadoFromCode(name) {
            let states = {
                "Acre": "AC",
                "Alagoas": "AL",
                "Amapá": "AP",
                "Amazonas": "AM",
                "Bahia": "BA",
                "Ceará": "CE",
                "Distrito Federal": "DF",
                "Espírito Santo": "ES",
                "Goiás": "GO",
                "Maranhão": "MA",
                "Mato Grosso": "MT",
                "Mato Grosso do Sul": "MS",
                "Minas Gerais": "MG",
                "Pará": "PA",
                "Paraíba": "PB",
                "Paraná": "PR",
                "Pernambuco": "PE",
                "Piauí": "PI",
                "Rio de Janeiro": "RJ",
                "Rio Grande do Norte": "RN",
                "Rio Grande do Sul": "RS",
                "Rondônia": "RO",
                "Roraima": "RR",
                "Santa Catarina": "SC",
                "São Paulo": "SP",
                "Sergipe": "SE",
                "Tocantins": "TO",
            };

            return states[name]||"";
        },

        selectPlace(place) {
            if (Object.keys(place).length==0) return;

            this.props.value = Object.assign(this.props.value, {
                lat: (place.lat? parseFloat(place.lat): 0),
                lng: (place.lon? parseFloat(place.lon): 0),
                route: place.address.road||"",
                number: '',
                complement: '',
                zipcode: place.address.postcode,
                district: place.address.suburb,
                city: place.address.city,
                state: place.address.state,
                st: this.getEstadoFromCode(place.address.state),
                country: place.address.country,
                co: place.address.country_code.toUpperCase(),
            });

            this.emit();
        },

        // https://nominatim.org/release-docs/develop/api/Search/
        osmSearch(params={}, isSearch=true) {
            params = Object.assign({
                format: "json",
                addressdetails: 1,
                extratags: 1,
                namedetails: 1,
                limit: 10,
            }, params);

            this.loading = true;
            this.$axios.get('https://nominatim.openstreetmap.org/search.php', {params}).then(resp => {
                this.loading = false;
                this.osmResponse(resp.data, isSearch);
            });
        },

        // https://nominatim.org/release-docs/develop/api/Reverse/
        osmReverse(params={}, isSearch=true) {
            params = Object.assign({
                format: "json",
                addressdetails: 1,
                extratags: 1,
                namedetails: 1,
                limit: 10,
                lat: 0,
                lon: 0,
            }, params);

            this.loading = true;
            this.$axios.get('https://nominatim.openstreetmap.org/reverse', {params}).then(resp => {
                this.loading = false;
                this.osmResponse([resp.data], isSearch);
            });
        },

        osmResponse(data, isSearch) {
            if (!isSearch && data[0]) {
                this.selectPlace(data[0]);
            }

            if (isSearch) {
                this.searchResults = data;
            }

            this.emit();
        },
    },

    data() {
        let data = {};

        data.loading = false;
        data.searchResults = [];

        data.props = Object.assign({}, this.$props);
        data.props.value = Object.assign({
            lat: 0,
            lng: 0,
            route: '',
            number: '',
            complement: '',
            zipcode: '',
            district: '',
            city: '',
            state: '',
            st: '',
            country: '',
            co: '',
        }, data.props.value);

        return data;
    },

    // mounted() {
    //     window.addEventListener('click', this.documentClickHandle);
    // },

    // beforeDestroy() {
    //     window.removeEventListener('click', this.documentClickHandle);
    // },
};</script>


<style>

</style>