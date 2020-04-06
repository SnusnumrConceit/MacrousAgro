import VueRouter from 'vue-router';

import Categories from '../components/admin/categories/index';
import CategoryForm from '../components/admin/categories/form';

import Articles from '../components/admin/articles/index';
import ArticleForm from '../components/admin/articles/form';

import Photos from '../components/admin/photos/index';
import PhotoForm from '../components/admin/photos/form';

import Videos from '../components/admin/videos/index';
import VideoForm from '../components/admin/videos/form';

import Users from '../components/admin/users/index';
import UserForm from '../components/admin/users/form';

import Products from '../components/admin/products/index';
import ProductForm from '../components/admin/products/form';

import Order from '../components/admin/orders/index';
import OrderForm from '../components/admin/orders/form';

const routes = [
  {
    path: '/admin/products',
    name: 'Products',
    component: Products,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/products/:id',
    name: 'ProductForm',
    component: ProductForm,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/orders',
    name: 'Order',
    component: Order,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/orders/:id',
    name: 'OrderForm',
    component: Order,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/categories',
    name: 'Categories',
    component: Categories,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/categories/:id',
    name: 'CategoryForm',
    component: CategoryForm,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },

  {
    path: '/admin/articles',
    name: 'Articles',
    component: Articles,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/articles/:id',
    name: 'ArticleForm',
    component: ArticleForm,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },

  {
    path: '/admin/photos',
    name: 'Photos',
    component: Photos,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/photos/:id',
    name: 'PhotoForm',
    component: PhotoForm,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },

  {
    path: '/admin/videos',
    name: 'Videos',
    component: Videos,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/videos/:id',
    name: 'VideoForm',
    component: VideoForm,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },

  {
    path: '/admin/users',
    name: 'Users',
    component: Users,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
  {
    path: '/admin/users/:id',
    name: 'UserForm',
    component: UserForm,
    meta: {
      auth: undefined
    },
    beforeEnter: null
  },
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes
});

export default router;