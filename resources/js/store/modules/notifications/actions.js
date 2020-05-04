const showNotification = ({commit}, {type, message, timer = null}) => {
  console.log(message, type, `notify.actions`);
  commit('SHOW_NOTIFICATION', {type, message, timer});
};

const hideNotification = ({commit}) => {
  commit('HIDE_NOTIFICATION');
};

export default {
  showNotification,
  hideNotification
};