import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import createPersistedState from "vuex-persistedstate";
// import VuexORM from "@vuex-orm/core";
// import Category from './models/category';

// const database = new VuexORM.Database;
// database.register(Category);

import auth          from './modules/auth/index';
import cart          from './modules/cart/index';
import category      from './modules/categories/index';
import errors        from './modules/errors/index';
import locale        from './modules/locale/index';
import notifications from './modules/notifications/index';

export const store = new Vuex.Store({
  modules: {
    auth,
    cart,
    category,
    errors,
    locale,
    notifications
  },

  plugins: [
    createPersistedState()
  ]
  // plugins: [VuexORM.install(database)]
});