const SET_CART_ORDERS_LOADING = (state, flag) => {
  state.loading = flag;
};

const SET_CART_ORDERS = (state, orders) => {
  state.orders = orders;
};

const SET_CART_ORDER = (state, order) => {
  state.order = order;
};

const SET_CART_LAST_PAGE = (state, lastPage) => {
  state.pagination.lastPage = lastPage;
};

const SET_CART_PAGE = (state, page) => {
  state.pagination.page = page;
};

const SET_CART_SEARCHING = (state, flag) => {
  state.searching = flag;
};

export default {
  SET_CART_ORDERS_LOADING,
  SET_CART_ORDERS,
  SET_CART_ORDER,
  SET_CART_LAST_PAGE,
  SET_CART_PAGE,
  SET_CART_SEARCHING
}