const admin = ({ next, store, nextMiddleware }) => {
  console.log(store.state.auth.user.role);
  if (store.state.auth.user.role !== 'administrator') {
    return nextMiddleware();
  }

  next();
};

export default admin;