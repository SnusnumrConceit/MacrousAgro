const customer = ({ next, store, nextMiddleware }) => {
  if (store.state.auth.user.role !== 'customer') {
    next('/');
  }

  return nextMiddleware();
};

export default customer;