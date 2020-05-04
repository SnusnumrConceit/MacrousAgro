import getters   from './getters';
import mutations from './mutations';
import actions   from './actions';

const state = {
  notification: {
    message: null,
    type: 'info',
    timer: 2000,
    show: false,
    icon: 'check-circle-outline'
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}