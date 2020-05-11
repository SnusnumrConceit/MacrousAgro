import axios from "axios";

const login = async ({state, commit}, login) => {
  try {
    await getCSRFCookies();
    const response = await axios.post('/login', {...login});

    await commit('SET_TOKEN', response.data.token);

    Vue.axios.defaults.headers.common = {'Authorization': `Bearer ${response.data.token}`}

    const user = await getUser();

    await commit('LOGIN', user);
  } catch (e) {
    commit('errors/SET_ERRORS', e.response.data.errors || e.response.data.message, {root: true});
  }
};

const register = async ({commit}, data) => {
  try {
    await getCSRFCookies();

    const response = await axios.post('/register', {...data});

    await commit('SET_TOKEN', response.data.token);

    Vue.axios.defaults.headers.common = {'Authorization': `Bearer ${response.data.token}`}

    const user = await getUser();

    await commit('REGISTER', user);
  } catch (e) {
    commit('errors/SET_ERRORS', e.response.data.errors || e.response.data.message, {root: true});
  }
};

const logout = async ({commit}) => {
  try {
    const response = await axios.post('/logout');

    commit('LOGOUT');

    commit('cart/SET_CART_ORDER', {positions: [], price: 0}, {root: true});
    sessionStorage.clear();

    document.location.href = '/';
  } catch (e) {
    commit('errors/SET_ERRORS', e.message || e.response.data.errors || e.response.data.message, {root: true});
  }
};

const getCSRFCookies = async () => {
  try {
    const cookies = await axios.get('/sanctum/csrf-cookie');
  } catch (e) {
    console.error(e.response.data);
  }
};

const getUser = async () => {
  try {
    const response = await axios.get('/user');

    return response.data.user;

  } catch (e) {
    console.error(e.response.data);
  }
};

export default {
  login,
  logout,
  register,
  getCSRFCookies
}