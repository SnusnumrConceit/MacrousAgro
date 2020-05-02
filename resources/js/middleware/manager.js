const manager = ({ next, store, nextMiddleware }) => {
  if (store.state.auth.user.role !== 'manager') {
    return nextMiddleware();
  }
  next();
};

export default manager;