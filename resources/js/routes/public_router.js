/** Dashboard Routes **/

import Dashboard from '../components/dashboard/dashboard';
import Cabinet from '../components/dashboard/cabinet';
import ProductDetail from '../components/dashboard/product/detail';
import CategoryProducts from '../components/dashboard/product/products';
import DashboardPhotos from '../components/dashboard/photos';
import DashboardVideos from '../components/dashboard/videos';

export const routes = [
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
    beforeEnter: null
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: ProductDetail,
    beforeEnter: null
  },
  {
    path: '/categories/:category_id/products',
    name: 'CategoryProducts',
    component: CategoryProducts,
  },
  {
    path: '/photos',
    name: 'DashboardPhotos',
    component: DashboardPhotos,
    beforeEnter: null
  },
  {
    path: '/videos',
    name: 'DashboardVideos',
    component: DashboardVideos,
    beforeEnter: null
  }
];
