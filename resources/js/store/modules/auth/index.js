import getters   from './getters';
import mutations from './mutations';
import actions   from './actions';

const state = {
  user: null,
  isAuthorized: localStorage.getItem('mocrous_session') || false,
  role: null
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
