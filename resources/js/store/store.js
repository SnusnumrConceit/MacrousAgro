import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import createPersistedState from "vuex-persistedstate";
// import VuexORM from "@vuex-orm/core";
// import Category from './models/category';

// const database = new VuexORM.Database;
// database.register(Category);

import category from './modules/categories/index';
import cart     from './modules/cart/index';
import auth     from './modules/auth/index';
import errors   from './modules/errors/index';

export const store = new Vuex.Store({
  modules: {
    category,
    cart,
    auth,
    errors
  },

  plugins: [
    createPersistedState()
  ]
  // plugins: [VuexORM.install(database)]
});