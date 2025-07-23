require('dotenv').config();

export default {
	server: {host:'0.0.0.0', port:3000},

	srcDir: 'resources/nuxt',
	/*
	** Nuxt rendering mode
	** See https://nuxtjs.org/api/configuration-mode
	*/
	ssr: false,

	env: {
		APP_NAME: process.env.APP_NAME,
		APP_DESCRIPTION: process.env.APP_DESCRIPTION,
	},

	/*
	** Headers of the page
	** See https://nuxtjs.org/api/configuration-head
	*/
	head: {
		titleTemplate: '%s - ' + (process.env.APP_NAME || ''),
		title: process.env.APP_NAME || '',
		link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
		meta: [
			{charset: 'utf-8'},
			{name: 'viewport', content: 'width=device-width, initial-scale=1'},
			{hid: 'description', name: 'description', content: process.env.npm_package_description || ''},
		],
	},

	/*
	** Customize the progress-bar color
	*/
	loading: { color: '#fff' },

	/*
	** Global CSS
	*/
	css: ['@/assets/app.scss'],

	/*
	** Plugins to load before mounting the App
	** https://nuxtjs.org/guide/plugins
	*/
	plugins: [
		'@/plugins/axios.js',
		'@/plugins/utils.js',
	],

	/*
	** Auto import components
	** See https://nuxtjs.org/api/configuration-components
	*/
	components: true,

	/*
	** Nuxt.js dev-modules
	*/
	buildModules: [
		// Doc: https://github.com/nuxt-community/eslint-module
		'@nuxtjs/pwa',
	],

	/*
	** Nuxt.js modules
	*/
	modules: [
		'nuxt-laravel',
		// Doc: https://axios.nuxtjs.org/usage
		'@nuxtjs/axios',

		// https://dev.auth.nuxtjs.org/guide/setup/
		'@nuxtjs/auth-next',

		// https://firebase.nuxtjs.org/guide/getting-started
		// ['@nuxtjs/firebase', {
		// 	config: {
		// 		apiKey: (process.env.FIREBASE_APIKEY || false),
		// 		authDomain: (process.env.FIREBASE_AUTHDOMAIN || false),
		// 		databaseURL: (process.env.FIREBASE_DATABASEURL || false),
		// 		projectId: (process.env.FIREBASE_PROJECTID || false),
		// 		storageBucket: (process.env.FIREBASE_STORAGEBUCKET || false),
		// 		messagingSenderId: (process.env.FIREBASE_MESSAGINGSENDERID || false),
		// 		appId: (process.env.FIREBASE_APPID || false),
		// 		measurementId: (process.env.FIREBASE_MEASUREMENTID || false),
		// 	},
		// 	services: {
		// 		database: true,
		// 	},
		// }],
	],

	auth: {
		// https://dev.auth.nuxtjs.org/api/options#redirect
		redirect: {
			login: '/auth',
			logout: '/auth',
			// callback: '/dashboard',
			home: '/dashboard',
		},

		strategies: {
			// https://dev.auth.nuxtjs.org/providers/laravel-jwt
			// https://github.com/nuxt-community/auth-module/blob/dev/src/providers/laravel/jwt/index.ts
			'jwt': {
				provider: 'laravel/jwt',
				url: '/',
				name: 'jwt',
				endpoints: {
					login: {method:'POST', url:'/api/auth/login'},
					refresh: {method:'POST', url:'/api/auth/refresh'},
					logout: {method:'POST', url:'/api/auth/logout'},
					user: {method:'POST', url:'/api/auth/me'},
				},
				token: {
					property: 'access_token',
					maxAge: (60 * 60),
				},
				refreshToken: {
					maxAge: (20160 * 60),
				},
			},
		},
	},

	/*
	** PWA module configuration
	** See https://pwa.nuxtjs.org/
	*/
	pwa: {
		
		// https://pwa.nuxtjs.org/icon
		icon: {
			source: './resources/nuxt/static/icon.png',
		},
		
		meta: {
			title: (process.env.APP_NAME||'APP_NAME'),
		},
	},

	manifest: {
		name: (process.env.APP_NAME||'APP_NAME'),
		short_name: (process.env.APP_NAME||'APP_NAME'),
		description: (process.env.APP_DESCRIPTION||'APP_DESCRIPTION'),
		display: "fullscreen",
		background_color: '#222222',
		lang: (process.env.APP_LANG || 'en'),
		useWebmanifestExtension: true,
	},

	workbox: {
		enabled: true,
		runtimeCaching: [
			{
				urlPattern: 'https://fonts.googleapis.com/.*',
				handler: 'cacheFirst',
				method: 'GET',
				strategyOptions: { cacheableResponse: { statuses: [0, 200] } }
			},
		]
	},

	/*
	** Axios module configuration
	** See https://axios.nuxtjs.org/options
	*/
	axios: {
		debug: false,
	},

	router: {
		// base: '/app/'
	},

	/*
	** Build configuration
	** See https://nuxtjs.org/api/configuration-build/
	*/
	build: {
		extend(config, ctx) {
			config.resolve.alias['vue'] = 'vue/dist/vue.common';
		},

		babel: {
			plugins: [
				['@babel/plugin-proposal-private-methods', { loose: true }],
			],
		},
	}
}
