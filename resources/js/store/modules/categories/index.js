/** здесь импортируются actions, mutations, getters **/
import getters from './getters';
import mutations from './mutations';
import actions from './actions';

const state = {
  categories: [],
  loading: false,

  pagination: {
    page: 1,
    lastPage: 1
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}