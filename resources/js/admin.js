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

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('errors', require('./components/common/Errors.vue').default);
import Admin from './components/admin/Admin';
// import Dashboard from './components/dashboard/dashboard';

import VueSweetalert2 from "vue-sweetalert2";
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);

import Vuetify from "vuetify";
Vue.use(Vuetify);

import i18n from "./i18n";

// axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`

import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';

import DebouncePlugin from './plugins/debounce';
Vue.use(DebouncePlugin);

let vuetify = new Vuetify({
  theme: {
    themes: {

    }
  }
});

import { store } from './store/store';

/** Настройка HTTP **/
import VueRouter from "vue-router";
import router from './routes/admin_router';
Vue.router = router;
Vue.use(VueRouter, router);

/** Настройка HTTP **/
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

/** Настройка авторизации **/
import auth from './auth';
import VueAuth from '@websanova/vue-auth';
Vue.use(VueAuth, auth);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  vuetify,
  components: {
    admin: Admin,
  }
});
