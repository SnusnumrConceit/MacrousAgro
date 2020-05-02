const login = async ({commit}, login) => {
  getCSRFCookies();

  try {
    const response = await axios.post('/login', {...login});

    const user = await getUser();

    commit('LOGIN', user);
  } catch (e) {
    commit('errors/SET_ERRORS', e.response.data.errors || e.response.data.message, {root: true});
  }
};

const register = async ({commit}, data) => {
  try {
    const response = await axios.post('/register', {...data});

    await getCSRFCookies();

    const user = await getUser();

    commit('REGISTER', user);
  } catch (e) {
    commit('errors/SET_ERRORS', e.response.data.errors || e.response.data.message, {root: true});
  }
};

const logout = async ({commit}) => {
  try {
    const response = await axios.post('/logout');

    commit('LOGOUT');

    document.location.href = '/';
  } catch (e) {
    commit('errors/SET_ERRORS', e.response.data.errors || e.response.data.message, {root: true});
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
  register
}