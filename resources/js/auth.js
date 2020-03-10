import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// import bearer from '@websanova/vue-auth/drivers/auth/bearer';
// import axios from 'axios';
// import router from 'vue-router';

const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'auth-token',
  tokenStore: ['localStorage'],

  registerData: {
    url: '/api/auth/register',
    method: 'POST',
    redirect: '/login'
  },

  loginData: {
    url: '/api/auth/login',
    method: 'POST',
    redirect: '/login',
    fetchUser: true
  },

  logoutData: {
    url: '/api/auth/logout',
    method: 'POST',
    redirect: '/',
    makeRequest: true
  },

  fetchUser: {
    url: '/api/auth/user',
    method: 'POST',
    enabled: true
  },

  refreshData: {
    url: '/api/auth/user',
    method: 'POST',
    enabled: true,
    interval: 30
  }
};

export default config;