const user = state => state.user;
const auth = state => state.isAuthorized;
const isManager = state => state.user.role === 'manager';
const isAdmin = state => state.user.role === 'administrator';

export default {
  user,
  auth,
  isAdmin,
  isManager
}