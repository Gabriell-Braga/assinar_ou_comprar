// import axios from 'axios';

// if (process.browser) {
//     axios.defaults.baseURL = `${window.location.protocol}//${window.location.host}`;
// }


// https://axios.nuxtjs.org/
export default function ({ $axios }) {
    $axios.onRequest((config) => {
        if (! config.url.startsWith('http')) {
            config.url = `${window.location.protocol}//${window.location.host}/`+ config.url.replace(/^\/|\/$/g, '');
        }

        return config;
    });
};