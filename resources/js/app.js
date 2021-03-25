require('./bootstrap');

window.Vue = require('vue');
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.api_token;

Vue.prototype.$http = axios;

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast);

Vue.component('chat-index', require('./components/chatIndex.vue').default);

window.eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

const app = new Vue({
    el: '#app',
});
