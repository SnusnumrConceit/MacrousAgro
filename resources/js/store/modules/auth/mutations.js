const LOGIN = (state, user) => {
  state.user = user;
};

const LOGOUT = (state) => {
  state.user = null;
  state.token = null;
};

const REGISTER = (state, user) => {
  state.user = user;
};

const SET_TOKEN = (state, token) => {
  console.log(token);
  state.token = token;
}

export default {
  LOGIN,
  LOGOUT,
  REGISTER,
  SET_TOKEN
}