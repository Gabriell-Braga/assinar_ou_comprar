import Vue from 'vue';

// https://github.com/avil13/vue-sweetalert2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

// https://element.eleme.io/
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/pt-br';
Vue.use(ElementUI, {locale});

// https://vuejs-tips.github.io/vue-the-mask/
import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask);


// https://vue2-leaflet.netlify.app/
import 'leaflet/dist/leaflet.css';
import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';
Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);


Vue.prototype.$log = function() {
	for(var i in arguments) {
		console.log(arguments[i]);
	}
};

Vue.prototype.$env = {
	NODE_ENV: process.env.NODE_ENV,
	APP_NAME: process.env.APP_NAME,
	APP_DESCRIPTION: process.env.APP_DESCRIPTION,
	APP_VERSION: (require('../../../package.json').version || '0.0.0'),
	APP_COLOR: (process.env.APP_COLOR || false),
	APP_LANG: (process.env.APP_LANG || false),
};

Vue.prototype.$copy = function(text) {
	return new Promise((resolve, reject) => {
		if (text.nodeType) {
			text = text.innerText;
		}
	
		let ta = Object.assign(document.createElement('textarea'), {
			style: {position:"fixed", top:-1000},
			value: text,
		});
	
		document.body.appendChild(ta);
		ta.focus();
		ta.select();
		let bool = document.execCommand('copy');
		document.body.removeChild(ta);
		
		if (bool) resolve(text);
		else reject('Erro ao copiar');
	});
};

Vue.prototype.$push = function(sessions, callback) {
	let preventFirstRun = true;
	this.$fire.database.ref('push').on('value', (snap) => {
		// if (preventFirstRun) {
		// 	preventFirstRun = false;
		// 	return callback({model: {}});
		// }

		let value = snap.val();
		sessions = Array.isArray(sessions)? sessions: [sessions];
		sessions.forEach(session => {
			if (value.session!=session) return;
			callback(value);
		});
	});
};

// Filters

import moment from 'moment';

Vue.filter('date', function(value, format='DD/MM/YYYY') {
	let d = moment(value);
	return d.isValid()? d.format(format): '';
});

Vue.filter('time', function(value, format='hh:mm') {
	let d = moment(value);
	return d.isValid()? d.format(format): '';
});

Vue.filter('datetime', function(value, format='DD/MM/YYYY à\\s hh:mm') {
	let d = moment(value);
	return d.isValid()? d.format(format): '';
});

Vue.filter('linkWhatsapp', function(number, text='') {
	number = (number||'').replace(/[^0-9]/g, '');
	text = encodeURI(text);
	return `https://api.whatsapp.com/send?phone=${number}&text=${text}`;
});

Vue.filter('money', function(value, signal='R$ ', decimals=2) {
	return signal+ parseFloat(value).toLocaleString('pt-BR', {
		minimumFractionDigits: decimals,
		maximumFractionDigits: decimals,
	});
});

Vue.filter('percent', function(value, percent) {
	return (percent/100) * value;
});

Vue.filter('toFixed', function(value, decimals=4) {
	return parseFloat(value).toFixed(decimals);
});

// Directives



// Redirect
// https://auth.nuxtjs.org/api/auth/#onredirecthandler