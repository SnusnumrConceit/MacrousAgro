const setErrors = ({commit}, errors) => {
  commit('SET_ERRORS', errors);
};

const resetErrors = ({commit}) => {
  commit('SET_ERRORS', []);
};

export default {
  setErrors,
  resetErrors
}