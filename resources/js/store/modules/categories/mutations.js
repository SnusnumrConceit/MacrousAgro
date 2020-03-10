const SET_CATEGORIES = (state, categories) => {
  state.categories = categories;
};

const SET_CATEGORIES_LOADING = (state, flag) => {
  state.loading = flag;
};

const SET_CATEGORIES_PAGE = (state, page) => {
  state.pagination.page = page;
};

const SET_CATEGORIES_LAST_PAGE = (state, page) => {
  state.pagination.lastPage = page;
};

export default {
  SET_CATEGORIES,
  SET_CATEGORIES_LOADING,
  SET_CATEGORIES_PAGE,
  SET_CATEGORIES_LAST_PAGE
}