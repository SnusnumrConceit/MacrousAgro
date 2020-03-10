import axios from 'axios';

/**
 * Получение списка заказов
 *
 * @param state
 * @param commit
 * @returns {Promise<void>}
 */

// TODO передавать параметр status, чтобы можно было фильтровать заказы - одна переменная на обработку и завершённые
const getOrders = async ({state, commit}) => {
  try {
    commit('SET_CART_LOADING', true);
    const response = await axios.get(`/api/cart/orders`, {
      page: state.pagination.page
    });

    commit('SET_CARD_ORDERS', response.data.orders);
    commit('SET_CARD_ORDERS_LAST_PAGE', response.data.orders.last_page);
  } catch (e) {

  } finally {
    commit('SET_CART_LOADING', false);
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

  order.products.push(product);
  order.price += parseInt(product.price);

  updateLocalStorageOrder(order);

  commit('SET_CART_ORDER', order);
};

/**
 * Удаление товара из корзины
 *
 * @param state
 * @param commit
 * @param product
 * @param total
 */
const removeFromCart = ({state, commit}, product, total = false) => {
  let order = state.order;

  order.products = total ? order.products.filter(orderProduct => orderProduct.id !== product.id) : [];

  order.price = order.products.length  ? order.price - parseInt(product.price) : 0;

  commit('SET_CART_ORDER', order);
};

/**
 * Создание заказа в системе
 *
 * @param state
 * @param commit
 * @returns {Promise<void>}
 */
const createOrder = async ({state, commit}) => {
  try {
    commit('SET_CART_ORDER_LOADING', true);
    const response = await axios.post('/api/orders', ...state.order);

    removeOrder({commit});

  } catch (e) {
    console.log(e.message);
  } finally {
    commit('SET_CART_ORDER_LOADING', false);
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
   products: []
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
  return order.products.some(orderProduct => orderProduct.name === product.name);
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