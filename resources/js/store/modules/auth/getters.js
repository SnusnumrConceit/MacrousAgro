const user = state => state.user;
const auth = state => state.isAuthorized;
const isAdmin = state    => state.user ? state.user.role === 'administrator' : false;
const isManager = state  => state.user ? state.user.role === 'manager' : false;
const isCustomer = state => state.user ? state.user.role === 'customer' : false;

export default {
  user,
  auth,
  isAdmin,
  isManager,
  isCustomer
}