<template><div class="ui-phone input-group" v-if="code">
  <select v-model="code" class="form-control border-right-0" style="max-width:130px;" @change="emit()">
    <option :value="c" v-for="c in codes">{{ c.countryName }} ({{ c.code }})</option>
  </select>
  <input type="text" class="form-control" v-mask="[...code.masks||[]]" v-model="props.value" @keyup="emit()" @change="emit()">
  <!-- <pre class="p-2 bg-dark">{{ $data }}</pre> -->
</div></template>


<style>

</style>


<script>
export default {
  props: {
    value: {default:''},
    country: {default:'BR'},
  },

  watch: {
    $props: {deep:true, handler(value) {
      this.props = Object.assign({}, value);
    }},
  },

  methods: {
    emit() {
      this.$emit('input', this.props.value);
    },

    getCode() {
      
      for(let i in this.codes) {
        let code = this.codes[i];

        if ((this.props.value||'').includes(code.code)) {
          return code;
        }
      }

      for(let i in this.codes) {
        let code = this.codes[i];

        if (code.country==this.props.country) {
          return code;
        }
      }

      return this.codes[0];
    },
  },

  mounted() {
    this.code = this.getCode();
  },

  data() {
    let data = {};
    data.props = Object.assign({}, this.$props);
    data.code = false;

    data.codes = [
      {
        "code": "+55",
        "country": "BR",
        "countryName": "Brasil",
        "masks": [
        "+55 (##) ####-####",
        "+55 (##) #####-####"
        ]
      },
      {
        "code": "+1",
        "country": "US",
        "countryName": "USA",
        "masks": [
        "+1 (###) ###-####"
        ]
      },
      {
        "code": "+213",
        "country": "DZ",
        "countryName": "Algeria",
        "masks": [
        "+213-##-###-####"
        ]
      },
      {
        "code": "+376",
        "country": "AD",
        "countryName": "Andorra",
        "masks": [
        "+376-###-###"
        ]
      },
      {
        "code": "+244",
        "country": "AO",
        "countryName": "Angola",
        "masks": [
        "+244 (###) ###-###"
        ]
      },
      {
        "code": "+1264",
        "country": "AI",
        "countryName": "Anguilla",
        "masks": [
        "+1 (264) ###-####"
        ]
      },
      {
        "code": "+1268",
        "country": "AG",
        "countryName": "Antigua & Barbuda",
        "masks": [
        "+1 (268) ###-####"
        ]
      },
      {
        "code": "+54",
        "country": "AR",
        "countryName": "Argentina",
        "masks": [
        "+54 (###) ###-####"
        ]
      },
      {
        "code": "+374",
        "country": "AM",
        "countryName": "Armenia",
        "masks": [
        "+374-##-###-###"
        ]
      },
      {
        "code": "+297",
        "country": "AW",
        "countryName": "Aruba",
        "masks": [
        "+297-###-####"
        ]
      },
      {
        "code": "+61",
        "country": "AU",
        "countryName": "Australia",
        "masks": [
        "+61-#-####-####"
        ]
      },
      {
        "code": "+43",
        "country": "AT",
        "countryName": "Austria",
        "masks": [
        "+43 (###) ###-####"
        ]
      },
      {
        "code": "+994",
        "country": "AZ",
        "countryName": "Azerbaijan",
        "masks": [
        "+994-##-###-##-##"
        ]
      },
      {
        "code": "+1242",
        "country": "BS",
        "countryName": "Bahamas",
        "masks": [
        "+1 (242) ###-####"
        ]
      },
      {
        "code": "+973",
        "country": "BH",
        "countryName": "Bahrain",
        "masks": [
        "+973-####-####"
        ]
      },
      {
        "code": "+880",
        "country": "BD",
        "countryName": "Bangladesh",
        "masks": [
        "+880-##-###-###"
        ]
      },
      {
        "code": "+1246",
        "country": "BB",
        "countryName": "Barbados",
        "masks": [
        "+1 (246) ###-####"
        ]
      },
      {
        "code": "+375",
        "country": "BY",
        "countryName": "Belarus",
        "masks": [
        "+375 (##) ###-##-##"
        ]
      },
      {
        "code": "+32",
        "country": "BE",
        "countryName": "Belgium",
        "masks": [
        "+32 (###) ###-###"
        ]
      },
      {
        "code": "+501",
        "country": "BZ",
        "countryName": "Belize",
        "masks": [
        "+501-###-####"
        ]
      },
      {
        "code": "+229",
        "country": "BJ",
        "countryName": "Benin",
        "masks": [
        "+229-##-##-####"
        ]
      },
      {
        "code": "+1441",
        "country": "BM",
        "countryName": "Bermuda",
        "masks": [
        "+1 (441) ###-####"
        ]
      },
      {
        "code": "+975",
        "country": "BT",
        "countryName": "Bhutan",
        "masks": [
        "+975-17-###-###",
        "+975-#-###-###"
        ]
      },
      {
        "code": "+591",
        "country": "BO",
        "countryName": "Bolivia",
        "masks": [
        "+591-#-###-####"
        ]
      },
      {
        "code": "+387",
        "country": "BA",
        "countryName": "Bosnia Herzegovina",
        "masks": [
        "+387-##-#####",
        "+387-##-####"
        ]
      },
      {
        "code": "+267",
        "country": "BW",
        "countryName": "Botswana",
        "masks": [
        "+267-##-###-###"
        ]
      },
      {
        "code": "+673",
        "country": "BN",
        "countryName": "Brunei",
        "masks": [
        "+673-###-####"
        ]
      },
      {
        "code": "+359",
        "country": "BG",
        "countryName": "Bulgaria",
        "masks": [
        "+359 (###) ###-###"
        ]
      },
      {
        "code": "+226",
        "country": "BF",
        "countryName": "Burkina Faso",
        "masks": [
        "+226-##-##-####"
        ]
      },
      {
        "code": "+257",
        "country": "BI",
        "countryName": "Burundi",
        "masks": [
        "+257-##-##-####"
        ]
      },
      {
        "code": "+855",
        "country": "KH",
        "countryName": "Cambodia",
        "masks": [
        "+855-##-###-###"
        ]
      },
      {
        "code": "+237",
        "country": "CM",
        "countryName": "Cameroon",
        "masks": [
        "+237-####-####"
        ]
      },
      {
        "code": "+1",
        "country": "CA",
        "countryName": "Canada",
        "masks": [
        "+1(###)###-####"
        ]
      },
      {
        "code": "+238",
        "country": "CV",
        "countryName": "Cape Verde Islands",
        "masks": [
        "+238(###)##-##"
        ]
      },
      {
        "code": "+1345",
        "country": "KY",
        "countryName": "Cayman Islands",
        "masks": [
        "+1(345)###-####"
        ]
      },
      {
        "code": "+236",
        "country": "CF",
        "countryName": "Central African Republic",
        "masks": [
        "+236-##-##-####"
        ]
      },
      {
        "code": "+56",
        "country": "CL",
        "countryName": "Chile",
        "masks": [
        "+56-#-####-####"
        ]
      },
      {
        "code": "+86",
        "country": "CN",
        "countryName": "China",
        "masks": [
        "+86(###)####-####",
        "+86(###)####-###",
        "+86-##-#####-#####"
        ]
      },
      {
        "code": "+57",
        "country": "CO",
        "countryName": "Colombia",
        "masks": [
        "+57(###)###-####"
        ]
      },
      {
        "code": "+269",
        "country": "KM",
        "countryName": "Comoros",
        "masks": [
        "+269-##-#####"
        ]
      },
      {
        "code": "+242",
        "country": "CG",
        "countryName": "Congo",
        "masks": [
        "+242-##-###-####"
        ]
      },
      {
        "code": "+682",
        "country": "CK",
        "countryName": "Cook Islands",
        "masks": [
        "+682-##-###"
        ]
      },
      {
        "code": "+506",
        "country": "CR",
        "countryName": "Costa Rica",
        "masks": [
        "+506-####-####"
        ]
      },
      {
        "code": "+385",
        "country": "HR",
        "countryName": "Croatia",
        "masks": [
        "+385-##-###-###"
        ]
      },
      {
        "code": "+53",
        "country": "CU",
        "countryName": "Cuba",
        "masks": [
        "+53-#-###-####"
        ]
      },
      {
        "code": "+90392",
        "country": "CY",
        "countryName": "Cyprus North",
        "masks": [
        "+357-##-###-###"
        ]
      },
      {
        "code": "+357",
        "country": "CY",
        "countryName": "Cyprus South",
        "masks": [
        "+357-##-###-###"
        ]
      },
      {
        "code": "+42",
        "country": "CZ",
        "countryName": "Czech Republic",
        "masks": [
        "+420(###)###-###"
        ]
      },
      {
        "code": "+45",
        "country": "DK",
        "countryName": "Denmark",
        "masks": [
        "+45-##-##-##-##"
        ]
      },
      {
        "code": "+253",
        "country": "DJ",
        "countryName": "Djibouti",
        "masks": [
        "+253-##-##-##-##"
        ]
      },
      {
        "code": "+1809",
        "country": "DM",
        "countryName": "Dominica",
        "masks": [
        "+1(767)###-####"
        ]
      },
      {
        "code": "+1809",
        "country": "DO",
        "countryName": "Dominican Republic",
        "masks": [
        "+1(809)###-####",
        "+1(829)###-####",
        "+1(849)###-####"
        ]
      },
      {
        "code": "+593",
        "country": "EC",
        "countryName": "Ecuador",
        "masks": [
        "+593-##-###-####",
        "+593-#-###-####"
        ]
      },
      {
        "code": "+20",
        "country": "EG",
        "countryName": "Egypt",
        "masks": [
        "+20(###)###-####"
        ]
      },
      {
        "code": "+503",
        "country": "SV",
        "countryName": "El Salvador",
        "masks": [
        "+503-##-##-####"
        ]
      },
      {
        "code": "+240",
        "country": "GQ",
        "countryName": "Equatorial Guinea",
        "masks": [
        "+240-##-###-####"
        ]
      },
      {
        "code": "+291",
        "country": "ER",
        "countryName": "Eritrea",
        "masks": [
        "+291-#-###-###"
        ]
      },
      {
        "code": "+372",
        "country": "EE",
        "countryName": "Estonia",
        "masks": [
        "+372-####-####",
        "+372-###-####"
        ]
      },
      {
        "code": "+251",
        "country": "ET",
        "countryName": "Ethiopia",
        "masks": [
        "+251-##-###-####"
        ]
      },
      {
        "code": "+500",
        "country": "FK",
        "countryName": "Falkland Islands",
        "masks": [
        "+500-#####"
        ]
      },
      {
        "code": "+298",
        "country": "FO",
        "countryName": "Faroe Islands",
        "masks": [
        "+298-###-###"
        ]
      },
      {
        "code": "+679",
        "country": "FJ",
        "countryName": "Fiji",
        "masks": [
        "+679-##-#####"
        ]
      },
      {
        "code": "+358",
        "country": "FI",
        "countryName": "Finland",
        "masks": [
        "+358(###)###-##-##"
        ]
      },
      {
        "code": "+33",
        "country": "FR",
        "countryName": "France",
        "masks": [
        "+262-#####-####",
        "+33(###)###-###",
        "+508-##-####",
        "+590(###)###-###"
        ]
      },
      {
        "code": "+594",
        "country": "GF",
        "countryName": "French Guiana",
        "masks": [
        "+594-#####-####"
        ]
      },
      {
        "code": "+689",
        "country": "PF",
        "countryName": "French Polynesia",
        "masks": [
        "+689-##-##-##"
        ]
      },
      {
        "code": "+241",
        "country": "GA",
        "countryName": "Gabon",
        "masks": [
        "+241-#-##-##-##"
        ]
      },
      {
        "code": "+220",
        "country": "GM",
        "countryName": "Gambia",
        "masks": [
        "+220(###)##-##"
        ]
      },
      {
        "code": "+7880",
        "country": "GE",
        "countryName": "Georgia",
        "masks": [
        "+995(###)###-###"
        ]
      },
      {
        "code": "+49",
        "country": "DE",
        "countryName": "Germany",
        "masks": [
        "+49(####)###-####",
        "+49(###)###-####",
        "+49(###)##-####",
        "+49(###)##-###",
        "+49(###)##-##",
        "+49-###-###"
        ]
      },
      {
        "code": "+233",
        "country": "GH",
        "countryName": "Ghana",
        "masks": [
        "+233(###)###-###"
        ]
      },
      {
        "code": "+350",
        "country": "GI",
        "countryName": "Gibraltar",
        "masks": [
        "+350-###-#####"
        ]
      },
      {
        "code": "+30",
        "country": "GR",
        "countryName": "Greece",
        "masks": [
        "+30(###)###-####"
        ]
      },
      {
        "code": "+299",
        "country": "GL",
        "countryName": "Greenland",
        "masks": [
        "+299-##-##-##"
        ]
      },
      {
        "code": "+1473",
        "country": "GD",
        "countryName": "Grenada",
        "masks": [
        "+1(473)###-####"
        ]
      },
      {
        "code": "+590",
        "country": "GP",
        "countryName": "Guadeloupe",
        "masks": []
      },
      {
        "code": "+671",
        "country": "GU",
        "countryName": "Guam",
        "masks": [
        "+1(671)###-####"
        ]
      },
      {
        "code": "+502",
        "country": "GT",
        "countryName": "Guatemala",
        "masks": [
        "+502-#-###-####"
        ]
      },
      {
        "code": "+224",
        "country": "GN",
        "countryName": "Guinea",
        "masks": [
        "+224-##-###-###"
        ]
      },
      {
        "code": "+245",
        "country": "GW",
        "countryName": "Guinea - Bissau",
        "masks": [
        "+245-#-######"
        ]
      },
      {
        "code": "+592",
        "country": "GY",
        "countryName": "Guyana",
        "masks": [
        "+592-###-####"
        ]
      },
      {
        "code": "+509",
        "country": "HT",
        "countryName": "Haiti",
        "masks": [
        "+509-##-##-####"
        ]
      },
      {
        "code": "+504",
        "country": "HN",
        "countryName": "Honduras",
        "masks": [
        "+504-####-####"
        ]
      },
      {
        "code": "+852",
        "country": "HK",
        "countryName": "Hong Kong",
        "masks": [
        "+852-####-####"
        ]
      },
      {
        "code": "+36",
        "country": "HU",
        "countryName": "Hungary",
        "masks": [
        "+36(###)###-###"
        ]
      },
      {
        "code": "+354",
        "country": "IS",
        "countryName": "Iceland",
        "masks": [
        "+354-###-####"
        ]
      },
      {
        "code": "+91",
        "country": "IN",
        "countryName": "India",
        "masks": [
        "+91(####)###-###"
        ]
      },
      {
        "code": "+62",
        "country": "ID",
        "countryName": "Indonesia",
        "masks": [
        "+62(8##)###-####",
        "+62-##-###-##",
        "+62-##-###-###",
        "+62-##-###-####",
        "+62(8##)###-###",
        "+62(8##)###-##-###"
        ]
      },
      {
        "code": "+98",
        "country": "IR",
        "countryName": "Iran",
        "masks": [
        "+98(###)###-####"
        ]
      },
      {
        "code": "+964",
        "country": "IQ",
        "countryName": "Iraq",
        "masks": [
        "+964(###)###-####"
        ]
      },
      {
        "code": "+353",
        "country": "IE",
        "countryName": "Ireland",
        "masks": [
        "+353(###)###-###"
        ]
      },
      {
        "code": "+972",
        "country": "IL",
        "countryName": "Israel",
        "masks": [
        "+972-5#-###-####",
        "+972-#-###-####"
        ]
      },
      {
        "code": "+39",
        "country": "IT",
        "countryName": "Italy",
        "masks": [
        "+39(###)####-###"
        ]
      },
      {
        "code": "+1876",
        "country": "JM",
        "countryName": "Jamaica",
        "masks": [
        "+1(876)###-####"
        ]
      },
      {
        "code": "+81",
        "country": "JP",
        "countryName": "Japan",
        "masks": [
        "+81-##-####-####",
        "+81(###)###-###"
        ]
      },
      {
        "code": "+962",
        "country": "JO",
        "countryName": "Jordan",
        "masks": [
        "+962-#-####-####"
        ]
      },
      {
        "code": "+7",
        "country": "KZ",
        "countryName": "Kazakhstan",
        "masks": [
        "+7(6##)###-##-##",
        "+7(7##)###-##-##"
        ]
      },
      {
        "code": "+254",
        "country": "KE",
        "countryName": "Kenya",
        "masks": [
        "+254-###-######"
        ]
      },
      {
        "code": "+686",
        "country": "KI",
        "countryName": "Kiribati",
        "masks": [
        "+686-##-###"
        ]
      },
      {
        "code": "+850",
        "country": "KP",
        "countryName": "Korea North",
        "masks": [
        "+850-191-###-####",
        "+850-##-###-###",
        "+850-###-####-###",
        "+850-###-###",
        "+850-####-####",
        "+850-####-#############"
        ]
      },
      {
        "code": "+82",
        "country": "KR",
        "countryName": "Korea South",
        "masks": [
        "+82-##-###-####"
        ]
      },
      {
        "code": "+965",
        "country": "KW",
        "countryName": "Kuwait",
        "masks": [
        "+965-####-####"
        ]
      },
      {
        "code": "+996",
        "country": "KG",
        "countryName": "Kyrgyzstan",
        "masks": [
        "+996(###)###-###"
        ]
      },
      {
        "code": "+856",
        "country": "LA",
        "countryName": "Laos",
        "masks": [
        "+856(20##)###-###",
        "+856-##-###-###"
        ]
      },
      {
        "code": "+371",
        "country": "LV",
        "countryName": "Latvia",
        "masks": [
        "+371-##-###-###"
        ]
      },
      {
        "code": "+961",
        "country": "LB",
        "countryName": "Lebanon",
        "masks": [
        "+961-##-###-###",
        "+961-#-###-###"
        ]
      },
      {
        "code": "+266",
        "country": "LS",
        "countryName": "Lesotho",
        "masks": [
        "+266-#-###-####"
        ]
      },
      {
        "code": "+231",
        "country": "LR",
        "countryName": "Liberia",
        "masks": [
        "+231-##-###-###"
        ]
      },
      {
        "code": "+218",
        "country": "LY",
        "countryName": "Libya",
        "masks": [
        "+218-##-###-###",
        "+218-21-###-####"
        ]
      },
      {
        "code": "+417",
        "country": "LI",
        "countryName": "Liechtenstein",
        "masks": [
        "+423(###)###-####"
        ]
      },
      {
        "code": "+370",
        "country": "LT",
        "countryName": "Lithuania",
        "masks": [
        "+370(###)##-###"
        ]
      },
      {
        "code": "+352",
        "country": "LU",
        "countryName": "Luxembourg",
        "masks": [
        "+352(###)###-###"
        ]
      },
      {
        "code": "+853",
        "country": "MO",
        "countryName": "Macao",
        "masks": [
        "+853-####-####"
        ]
      },
      {
        "code": "+389",
        "country": "MK",
        "countryName": "Macedonia",
        "masks": [
        "+389-##-###-###"
        ]
      },
      {
        "code": "+261",
        "country": "MG",
        "countryName": "Madagascar",
        "masks": [
        "+261-##-##-#####"
        ]
      },
      {
        "code": "+265",
        "country": "MW",
        "countryName": "Malawi",
        "masks": [
        "+265-1-###-###",
        "+265-#-####-####"
        ]
      },
      {
        "code": "+60",
        "country": "MY",
        "countryName": "Malaysia",
        "masks": [
        "+60-##-###-####",
        "+60(###)###-###",
        "+60-##-###-###",
        "+60-#-###-###"
        ]
      },
      {
        "code": "+960",
        "country": "MV",
        "countryName": "Maldives",
        "masks": [
        "+960-###-####"
        ]
      },
      {
        "code": "+223",
        "country": "ML",
        "countryName": "Mali",
        "masks": [
        "+223-##-##-####"
        ]
      },
      {
        "code": "+356",
        "country": "MT",
        "countryName": "Malta",
        "masks": [
        "+356-####-####"
        ]
      },
      {
        "code": "+692",
        "country": "MH",
        "countryName": "Marshall Islands",
        "masks": [
        "+692-###-####"
        ]
      },
      {
        "code": "+596",
        "country": "MQ",
        "countryName": "Martinique",
        "masks": [
        "+596(###)##-##-##"
        ]
      },
      {
        "code": "+222",
        "country": "MR",
        "countryName": "Mauritania",
        "masks": [
        "+222-##-##-####"
        ]
      },
      {
        "code": "+269",
        "country": "YT",
        "countryName": "Mayotte",
        "masks": []
      },
      {
        "code": "+52",
        "country": "MX",
        "countryName": "Mexico",
        "masks": [
        "+52(###)###-####",
        "+52-##-##-####"
        ]
      },
      {
        "code": "+691",
        "country": "FM",
        "countryName": "Micronesia",
        "masks": [
        "+691-###-####"
        ]
      },
      {
        "code": "+373",
        "country": "MD",
        "countryName": "Moldova",
        "masks": [
        "+373-####-####"
        ]
      },
      {
        "code": "+377",
        "country": "MC",
        "countryName": "Monaco",
        "masks": [
        "+377(###)###-###",
        "+377-##-###-###"
        ]
      },
      {
        "code": "+976",
        "country": "MN",
        "countryName": "Mongolia",
        "masks": [
        "+976-##-##-####"
        ]
      },
      {
        "code": "+1664",
        "country": "MS",
        "countryName": "Montserrat",
        "masks": [
        "+1(664)###-####"
        ]
      },
      {
        "code": "+212",
        "country": "MA",
        "countryName": "Morocco",
        "masks": [
        "+212-##-####-###"
        ]
      },
      {
        "code": "+258",
        "country": "MZ",
        "countryName": "Mozambique",
        "masks": [
        "+258-##-###-###"
        ]
      },
      {
        "code": "+95",
        "country": "MN",
        "countryName": "Myanmar",
        "masks": [
        "+976-##-##-####"
        ]
      },
      {
        "code": "+264",
        "country": "NA",
        "countryName": "Namibia",
        "masks": [
        "+264-##-###-####"
        ]
      },
      {
        "code": "+674",
        "country": "NR",
        "countryName": "Nauru",
        "masks": [
        "+674-###-####"
        ]
      },
      {
        "code": "+977",
        "country": "NP",
        "countryName": "Nepal",
        "masks": [
        "+977-##-###-###"
        ]
      },
      {
        "code": "+31",
        "country": "NL",
        "countryName": "Netherlands",
        "masks": [
        "+31-##-###-####"
        ]
      },
      {
        "code": "+687",
        "country": "NC",
        "countryName": "New Caledonia",
        "masks": [
        "+687-##-####"
        ]
      },
      {
        "code": "+64",
        "country": "NZ",
        "countryName": "New Zealand",
        "masks": [
        "+64(###)###-###",
        "+64-##-###-###",
        "+64(###)###-####"
        ]
      },
      {
        "code": "+505",
        "country": "NI",
        "countryName": "Nicaragua",
        "masks": [
        "+505-####-####"
        ]
      },
      {
        "code": "+227",
        "country": "NE",
        "countryName": "Niger",
        "masks": [
        "+227-##-##-####"
        ]
      },
      {
        "code": "+234",
        "country": "NG",
        "countryName": "Nigeria",
        "masks": [
        "+234(###)###-####",
        "+234-##-###-###",
        "+234-##-###-##",
        "+234(###)###-####"
        ]
      },
      {
        "code": "+683",
        "country": "NU",
        "countryName": "Niue",
        "masks": [
        "+683-####"
        ]
      },
      {
        "code": "+672",
        "country": "NF",
        "countryName": "Norfolk Islands",
        "masks": [
        "+672-3##-###"
        ]
      },
      {
        "code": "+670",
        "country": "NP",
        "countryName": "Northern Marianas",
        "masks": [
        "+977-##-###-###"
        ]
      },
      {
        "code": "+47",
        "country": "NO",
        "countryName": "Norway",
        "masks": [
        "+47(###)##-###"
        ]
      },
      {
        "code": "+968",
        "country": "OM",
        "countryName": "Oman",
        "masks": [
        "+968-##-###-###"
        ]
      },
      {
        "code": "+680",
        "country": "PW",
        "countryName": "Palau",
        "masks": [
        "+680-###-####"
        ]
      },
      {
        "code": "+507",
        "country": "PA",
        "countryName": "Panama",
        "masks": [
        "+507-###-####"
        ]
      },
      {
        "code": "+675",
        "country": "PG",
        "countryName": "Papua New Guinea",
        "masks": [
        "+675(###)##-###"
        ]
      },
      {
        "code": "+595",
        "country": "PY",
        "countryName": "Paraguay",
        "masks": [
        "+595(###)###-###"
        ]
      },
      {
        "code": "+51",
        "country": "PE",
        "countryName": "Peru",
        "masks": [
        "+51(###)###-###"
        ]
      },
      {
        "code": "+63",
        "country": "PH",
        "countryName": "Philippines",
        "masks": [
        "+63(###)###-####"
        ]
      },
      {
        "code": "+48",
        "country": "PL",
        "countryName": "Poland",
        "masks": [
        "+48(###)###-###"
        ]
      },
      {
        "code": "+351",
        "country": "PT",
        "countryName": "Portugal",
        "masks": [
        "+351-##-###-####"
        ]
      },
      {
        "code": "+1787",
        "country": "PR",
        "countryName": "Puerto Rico",
        "masks": []
      },
      {
        "code": "+974",
        "country": "QA",
        "countryName": "Qatar",
        "masks": [
        "+974-####-####"
        ]
      },
      {
        "code": "+262",
        "country": "RE",
        "countryName": "Reunion",
        "masks": [
        "+262-#####-####"
        ]
      },
      {
        "code": "+40",
        "country": "RO",
        "countryName": "Romania",
        "masks": [
        "+40-##-###-####"
        ]
      },
      {
        "code": "+7",
        "country": "RU",
        "countryName": "Russia",
        "masks": [
        "+7(###)###-##-##"
        ]
      },
      {
        "code": "+250",
        "country": "RW",
        "countryName": "Rwanda",
        "masks": [
        "+250(###)###-###"
        ]
      },
      {
        "code": "+378",
        "country": "SM",
        "countryName": "San Marino",
        "masks": [
        "+378-####-######"
        ]
      },
      {
        "code": "+239",
        "country": "ST",
        "countryName": "Sao Tome & Principe",
        "masks": [
        "+239-##-#####"
        ]
      },
      {
        "code": "+966",
        "country": "SA",
        "countryName": "Saudi Arabia",
        "masks": [
        "+966-5-####-####",
        "+966-#-###-####"
        ]
      },
      {
        "code": "+221",
        "country": "SN",
        "countryName": "Senegal",
        "masks": [
        "+221-##-###-####"
        ]
      },
      {
        "code": "+381",
        "country": "CS",
        "countryName": "Serbia",
        "masks": []
      },
      {
        "code": "+248",
        "country": "SC",
        "countryName": "Seychelles",
        "masks": [
        "+248-#-###-###"
        ]
      },
      {
        "code": "+232",
        "country": "SL",
        "countryName": "Sierra Leone",
        "masks": [
        "+232-##-######"
        ]
      },
      {
        "code": "+65",
        "country": "SG",
        "countryName": "Singapore",
        "masks": [
        "+65-####-####"
        ]
      },
      {
        "code": "+421",
        "country": "SK",
        "countryName": "Slovak Republic",
        "masks": [
        "+421(###)###-###"
        ]
      },
      {
        "code": "+386",
        "country": "SI",
        "countryName": "Slovenia",
        "masks": [
        "+386-##-###-###"
        ]
      },
      {
        "code": "+677",
        "country": "SB",
        "countryName": "Solomon Islands",
        "masks": [
        "+677-###-####",
        "+677-#####"
        ]
      },
      {
        "code": "+252",
        "country": "SO",
        "countryName": "Somalia",
        "masks": [
        "+252-##-###-###",
        "+252-#-###-###",
        "+252-#-###-###"
        ]
      },
      {
        "code": "+27",
        "country": "ZA",
        "countryName": "South Africa",
        "masks": [
        "+27-##-###-####"
        ]
      },
      {
        "code": "+34",
        "country": "ES",
        "countryName": "Spain",
        "masks": [
        "+34(###)###-###"
        ]
      },
      {
        "code": "+94",
        "country": "LK",
        "countryName": "Sri Lanka",
        "masks": [
        "+94-##-###-####"
        ]
      },
      {
        "code": "+290",
        "country": "SH",
        "countryName": "St. Helena",
        "masks": [
        "+290-####",
        "+290-####"
        ]
      },
      {
        "code": "+1869",
        "country": "KN",
        "countryName": "St. Kitts",
        "masks": [
        "+1(869)###-####"
        ]
      },
      {
        "code": "+1758",
        "country": "SC",
        "countryName": "St. Lucia",
        "masks": [
        "+248-#-###-###"
        ]
      },
      {
        "code": "+249",
        "country": "SD",
        "countryName": "Sudan",
        "masks": [
        "+249-##-###-####"
        ]
      },
      {
        "code": "+597",
        "country": "SR",
        "countryName": "Suriname",
        "masks": [
        "+597-###-####",
        "+597-###-###"
        ]
      },
      {
        "code": "+268",
        "country": "SZ",
        "countryName": "Swaziland",
        "masks": [
        "+268-##-##-####"
        ]
      },
      {
        "code": "+46",
        "country": "SE",
        "countryName": "Sweden",
        "masks": [
        "+46-##-###-####"
        ]
      },
      {
        "code": "+41",
        "country": "CH",
        "countryName": "Switzerland",
        "masks": [
        "+41-##-###-####"
        ]
      },
      {
        "code": "+963",
        "country": "SI",
        "countryName": "Syria",
        "masks": [
        "+386-##-###-###"
        ]
      },
      {
        "code": "+886",
        "country": "TW",
        "countryName": "Taiwan",
        "masks": [
        "+886-#-####-####",
        "+886-####-####"
        ]
      },
      {
        "code": "+7",
        "country": "TJ",
        "countryName": "Tajikstan",
        "masks": [
        "+992-##-###-####"
        ]
      },
      {
        "code": "+66",
        "country": "TH",
        "countryName": "Thailand",
        "masks": [
        "+66-##-###-####",
        "+66-##-###-###"
        ]
      },
      {
        "code": "+228",
        "country": "TG",
        "countryName": "Togo",
        "masks": [
        "+228-##-###-###"
        ]
      },
      {
        "code": "+676",
        "country": "TO",
        "countryName": "Tonga",
        "masks": [
        "+676-#####"
        ]
      },
      {
        "code": "+1868",
        "country": "TT",
        "countryName": "Trinidad & Tobago",
        "masks": [
        "+1(868)###-####"
        ]
      },
      {
        "code": "+216",
        "country": "TN",
        "countryName": "Tunisia",
        "masks": [
        "+216-##-###-###"
        ]
      },
      {
        "code": "+90",
        "country": "TR",
        "countryName": "Turkey",
        "masks": [
        "+90(###)###-####"
        ]
      },
      {
        "code": "+7",
        "country": "TM",
        "countryName": "Turkmenistan",
        "masks": [
        "+993-#-###-####"
        ]
      },
      {
        "code": "+993",
        "country": "TM",
        "countryName": "Turkmenistan",
        "masks": [
        "+993-#-###-####"
        ]
      },
      {
        "code": "+1649",
        "country": "TC",
        "countryName": "Turks & Caicos Islands",
        "masks": [
        "+1(649)###-####"
        ]
      },
      {
        "code": "+688",
        "country": "TV",
        "countryName": "Tuvalu",
        "masks": [
        "+688-90####",
        "+688-2####"
        ]
      },
      {
        "code": "+256",
        "country": "UG",
        "countryName": "Uganda",
        "masks": [
        "+256(###)###-###"
        ]
      },
      {
        "code": "+44",
        "country": "GB",
        "countryName": "UK",
        "masks": []
      },
      {
        "code": "+380",
        "country": "UA",
        "countryName": "Ukraine",
        "masks": [
        "+380(##)###-##-##"
        ]
      },
      {
        "code": "+971",
        "country": "AE",
        "countryName": "United Arab Emirates",
        "masks": [
        "+971-5#-###-####",
        "+971-#-###-####"
        ]
      },
      {
        "code": "+598",
        "country": "UY",
        "countryName": "Uruguay",
        "masks": [
        "+598-#-###-##-##"
        ]
      },
      {
        "code": "+1",
        "country": "US",
        "countryName": "USA",
        "masks": [
        "+1(###)###-####"
        ]
      },
      {
        "code": "+7",
        "country": "UZ",
        "countryName": "Uzbekistan",
        "masks": [
        "+998-##-###-####"
        ]
      },
      {
        "code": "+678",
        "country": "VU",
        "countryName": "Vanuatu",
        "masks": [
        "+678-##-#####",
        "+678-#####"
        ]
      },
      {
        "code": "+379",
        "country": "VA",
        "countryName": "Vatican City",
        "masks": [
        "+39-6-698-#####"
        ]
      },
      {
        "code": "+58",
        "country": "VE",
        "countryName": "Venezuela",
        "masks": [
        "+58(###)###-####"
        ]
      },
      {
        "code": "+84",
        "country": "VN",
        "countryName": "Vietnam",
        "masks": [
        "+84-##-####-###",
        "+84(###)####-###"
        ]
      },
      {
        "code": "+84",
        "country": "VG",
        "countryName": "Virgin Islands - British",
        "masks": [
        "+1(284)###-####"
        ]
      },
      {
        "code": "+84",
        "country": "VI",
        "countryName": "Virgin Islands - US",
        "masks": [
        "+1(340)###-####"
        ]
      },
      {
        "code": "+681",
        "country": "WF",
        "countryName": "Wallis & Futuna",
        "masks": [
        "+681-##-####"
        ]
      },
      {
        "code": "+969",
        "country": "YE",
        "countryName": "Yemen (North",
        "masks": [
        "+967-###-###-###",
        "+967-#-###-###",
        "+967-##-###-###"
        ]
      },
      {
        "code": "+967",
        "country": "YE",
        "countryName": "Yemen (South",
        "masks": [
        "+967-###-###-###",
        "+967-#-###-###",
        "+967-##-###-###"
        ]
      },
      {
        "code": "+260",
        "country": "ZM",
        "countryName": "Zambia",
        "masks": [
        "+260-##-###-####"
        ]
      },
      {
        "code": "+263",
        "country": "ZW",
        "countryName": "Zimbabwe",
        "masks": [
        "+263-#-######"
        ]
      }
    ];

    return data;
  },
};
</script>
