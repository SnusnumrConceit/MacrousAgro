/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('errors', require('./components/common/Errors.vue').default);
Vue.component('notification', require('./components/common/Notification.vue').default);
// import Admin from './components/admin/admin';
import Dashboard from './components/dashboard/Dashboard';

import VueSweetalert2 from "vue-sweetalert2";
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);

import Debounce from './plugins/debounce';
Vue.use(Debounce, '$debounce');

import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import Vuetify from "vuetify";
Vue.use(Vuetify);

import i18n from "./i18n";

/** Настройка роутера **/
import router from './routes/public_router';
Vue.router = router;
import VueRouter from "vue-router";
Vue.use(VueRouter);

/** Импорт хранилища данных Vuex **/
import { store } from './store/store';

/** Смена языка **/
i18n.locale = store.state.locale.locale;

/** Настройка HTTP **/
import axios from 'axios';
import VueAxios from 'vue-axios';

axios.defaults.withCredentials = true;
axios.defaults.headers.common = {
  'Authorization': `Bearer ${store.state.auth.token}`,
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  'X-Requested-With': 'XMLHttpRequest',
  'X-LOCALE': i18n.locale
};

Vue.use(VueAxios, axios);

let vuetify = new Vuetify({
   theme: {
       themes: {

       }
   }
});

const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  vuetify,
  components: {
    dashboard: Dashboard
  }
});
