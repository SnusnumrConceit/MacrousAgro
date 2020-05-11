const user = state => state.user;
const auth = state => state.isAuthorized;
const isAdmin = state    => state.user ? state.user.role.toLowerCase() === 'administrator' : false;
const isManager = state  => state.user ? state.user.role.toLowerCase() === 'manager' : false;
const isCustomer = state => state.user ? state.user.role.toLowerCase() === 'customer' : false;

export default {
  user,
  auth,
  isAdmin,
  isManager,
  isCustomer
}