import getters from './getters'
import mutations from './mutations'
import actions from './actions'

const state = {
  orders: [],

  order: {
    positions: [],
    price: 0,
    title: ''
  },

  loading: false,
  searching: false,

  pagination: {
    page: 1,
    lastPage: 1
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}