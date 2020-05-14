export default {
  methods: {
    formatFormDataArg(prop) {
      switch (typeof prop) {
        case 'boolean':
          return Number(prop);
        case 'null':
          return null;
        case 'undefined':
          return undefined;
        default: return prop;
      }
    }
  }
}