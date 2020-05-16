import i18n from '../../../i18n';

const setLocale = async ({commit}, locale) => {
  const response = await axios.get(`/lang/{locale}`);
  i18n.locale = locale;

  commit('SET_LOCALE', locale);
};

export default {
  setLocale
}