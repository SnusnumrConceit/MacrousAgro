const LOGIN = (state, user) => {
  state.user = user;
};

const LOGOUT = (state) => {
  state.user = null;
};

const REGISTER = (state, user) => {
  state.user = user;
};

export default {
  LOGIN,
  LOGOUT,
  REGISTER
}