/** Dashboard Routes **/
import VueRouter from 'vue-router';

import Dashboard from '../components/dashboard/dashboard';
import Cabinet from '../components/dashboard/cabinet';
import ProductDetail from '../components/dashboard/product/detail';
import CategoryProducts from '../components/dashboard/product/products';
import DashboardPhotos from '../components/dashboard/photos';
import DashboardVideos from '../components/dashboard/videos';

const routes = [
  // {
  //   path: '/',
  //   name: 'Dashboard',
  //   component: Dashboard,
  //   beforeEnter: null
  // },
  {
    path: '/cabinet',
    name: 'Cabinet',
    component: Cabinet,
    meta: {
      auth: undefined // true
    },
    beforeEnter: null
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: ProductDetail,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/categories/:category_id/products',
    name: 'CategoryProducts',
    component: CategoryProducts,
    meta: {
      auth: undefined
    },
  },
  {
    path: '/photos',
    name: 'DashboardPhotos',
    component: DashboardPhotos,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/videos',
    name: 'DashboardVideos',
    component: DashboardVideos,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  }
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes
});

export default router;
