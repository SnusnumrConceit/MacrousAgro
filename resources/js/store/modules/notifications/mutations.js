const SHOW_NOTIFICATION = (state, {type, message, timer = null}) => {
  console.log(type, message, `notify.mutations`);
  state.notification.type = type;
  state.notification.message = message;
  if (timer) {
    state.notification.timer = timer;
  }
  state.notification.icon = getIcon(type);
  state.notification.show = true;
};

const getIcon = (type) => {
  switch (type) {
    case 'error': return 'mdi-alert-outline';
    case 'success': return 'mdi-check-circle-outline';
  }
};

const HIDE_NOTIFICATION = (state) => {
  state.notification.show = false;
  state.notification.type = 'info';
  state.notification.message = null;
};

export default {
  SHOW_NOTIFICATION,
  HIDE_NOTIFICATION
}