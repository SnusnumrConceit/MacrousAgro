const translate = (string) => app[0].__vue__.$t(string);

/**
 * Получение списка заказов
 *
 * @param state
 * @param commit
 * @param dispatch
 *
 * @returns {Promise<void>}
 */
const getOrders = async ({state, commit, dispatch}) => {
  try {
    commit('SET_CART_ORDERS_LOADING', true);

    dispatch('auth/getCSRFCookies', null, {root: true});

    const response = await axios.get(`/api/cart/orders`, {
      page: state.pagination.page
    });

    commit('SET_CART_ORDERS', response.data);
  } catch (e) {

  } finally {
    commit('SET_CART_ORDERS_LOADING', false);
  }
};

/**
 * Получение созданного заказа
 *
 * @param state
 * @param commit
 */
const getOrder = ({state, commit}) => {
  let order = localStorage.getItem('order');
  if (order !== undefined) {
    commit('SET_CART_ORDER', JSON.parse(order));
  }
};

/**
 * Добавление товара в корзину
 *
 * @param state
 * @param commit
 * @param product
 * @returns {boolean}
 */
const addToCart = ({state, commit}, product) => {
  let order = state.order;

  order.title = crypto.getRandomValues(new Uint8Array(12)).toString();

  if (validateOrderExisting(order, product)) {
    return false;
  }

  order.positions.push({product: product});
  order.price += parseInt(product.price);

  updateLocalStorageOrder(order);

  commit('SET_CART_ORDER', order);

  commit('notifications/SHOW_NOTIFICATION', {type: 'success', message: translate('orders.added')}, {root: true})
};

/**
 * Удаление товара из корзины
 *
 * @param state
 * @param commit
 * @param product
 * @param total
 */
const removeFromCart = ({state, commit}, {product, total = false}) => {
  let order = state.order;

  order.positions = ! total ? order.positions.filter(position => position.product.id !== product.id) : [];

  order.price = order.positions.length  ? order.price - parseInt(product.price) : 0;

  commit('SET_CART_ORDER', order);

  commit('notifications/SHOW_NOTIFICATION', {type: 'success', message: translate('orders.removed')}, {root: true})
};

/**
 * Создание заказа в системе
 *
 * @param state
 * @param commit
 * @param dispatch
 *
 * @returns {Promise<void>}
 */
const createOrder = async ({state, commit, dispatch}) => {
  try {
    commit('SET_CART_ORDERS_LOADING', true);

    console.log(state.order.positions);
    let products = state.order.positions.map(position => position.product.id);
    console.log(products, 'products');
    const response = await axios.post('/api/cart/orders', {products: products});

    commit('notifications/SHOW_NOTIFICATION', {type: 'success', message: response.data.message}, {root: true})
    dispatch('removeOrder');

    dispatch('getOrders');

  } catch (e) {
    commit('errors/SET_ERRORS', e.response.data.message, {root:true});
    console.error(e.message);
  } finally {
    commit('SET_CART_ORDERS_LOADING', false);
  }
};

/**
 * Удаление текущего созданного заказа
 *
 * @param commit
 */
const removeOrder = ({commit}) => {
 commit('SET_CART_ORDER', {
   price: 0,
   title: '',
   positions: []
 });
};

/**
 * Отмена текущего созданного заказа
 *
 * @param state
 * @param commit
 * @param id
 * @returns {Promise<void>}
 */
const cancelOrder = async ({state, commit}, id) => {
  try {
    const response = await axios.post(`/api/orders/${id}/cancel`);

    getOrders({state, commit});
  } catch (e) {

  } finally {

  }
};

/**
 * Проверка наличия товара в корзине
 *
 * @param order
 * @param product
 * @returns {*}
 */
const validateOrderExisting = (order, product) => {
  return order.positions.some(position => position.product.title === product.title)
};

/**
 * Обновление информации о заказе в localStorage
 *  TODO нужно ли вообще трогать localStorage, если включен persistant?
 * @param order
 */
const updateLocalStorageOrder = (order) => {
  removeLocalStorageOrder();

  localStorage.setItem('order', JSON.stringify(order));
};

const removeLocalStorageOrder = () => {
  if (localStorage.getItem('order') !== undefined) {
    localStorage.removeItem('order');
  }
};

export default {
  getOrders,
  getOrder,
  addToCart,
  removeFromCart,

  createOrder,
  removeOrder,
  cancelOrder
}