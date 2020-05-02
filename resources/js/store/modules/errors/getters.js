const errors = (state) => {
  switch (typeof state.errors) {
    case 'object': return state.errors;
    case 'string': return {'error': [state.errors]};
  }
};

export default {
  errors
}