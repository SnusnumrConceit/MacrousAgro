import VueRouter from 'vue-router';
import { store } from '../store/store';

import Categories       from '../components/admin/categories/Categories';
import CategoryForm     from '../components/admin/categories/CategoryForm';

import Articles         from '../components/admin/articles/Articles';
import ArticleForm      from '../components/admin/articles/ArticleForm';

import Photos           from '../components/admin/photos/Photos';
import PhotoForm        from '../components/admin/photos/PhotoForm';

import Videos           from '../components/admin/videos/Videos';
import VideoForm        from '../components/admin/videos/VideoForm';

import Users            from '../components/admin/users/Users';
import UserForm         from '../components/admin/users/UserForm';

import Products         from '../components/admin/products/Products';
import ProductForm      from '../components/admin/products/ProductForm';

import Orders           from '../components/admin/orders/Orders';

import Roles           from '../components/admin/roles/Roles';
import RoleForm        from '../components/admin/roles/RoleForm';

// import admin from '../middleware/admin';
// import manager from '../middleware/manager';
// import middlewarePipeline from '../middleware/middlewarePipeline';

const routes = [

  /** Articles routes **/
  {
    path: '/admin/articles',
    name: 'Articles',
    component: Articles,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },
  {
    path: '/admin/articles/:id',
    name: 'ArticleForm',
    component: ArticleForm,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },

  /** Categories routes **/
  {
    path: '/admin/categories',
    name: 'Categories',
    component: Categories,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },
  {
    path: '/admin/categories/:id',
    name: 'CategoryForm',
    component: CategoryForm,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },

  /** Orders routes **/
  {
    path: '/admin/orders',
    name: 'Orders',
    component: Orders,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },

  /** Photos routes **/
  {
    path: '/admin/photos',
    name: 'Photos',
    component: Photos,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },
  {
    path: '/admin/photos/:id',
    name: 'PhotoForm',
    component: PhotoForm,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },

  /** Products routes **/
  {
    path: '/admin/products',
    name: 'Products',
    component: Products,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },
  {
    path: '/admin/products/:id',
    name: 'ProductForm',
    component: ProductForm,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },

  /** Roles routes **/
  {
    path: '/admin/roles',
    name: 'Roles',
    component: Roles,
    meta: {
      middleware: [
        'administrator'
      ]
    }
  },
  {
    path: '/admin/roles/:id',
    name: 'RoleForm',
    component: RoleForm,
    meta: {
      middleware: [
        'administrator'
      ]
    }
  },

  /** Videos routes **/
  {
    path: '/admin/videos',
    name: 'Videos',
    component: Videos,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },
  {
    path: '/admin/videos/:id',
    name: 'VideoForm',
    component: VideoForm,
    meta: {
      middleware: [
        'administrator',
        'manager'
      ]
    }
  },

  /** Users routes **/
  {
    path: '/admin/users',
    name: 'Users',
    component: Users,
    meta: {
      middleware: [
        'administrator'
      ]
    }
  },
  {
    path: '/admin/users/:id',
    name: 'UserForm',
    component: UserForm,
    meta: {
      middleware: [
        'administrator'
      ]
    }
  },
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes
});

/** Middleware Guard **/
router.beforeEach((to, from, next) => {
  if (! store.state.auth.user || ! to.meta.middleware) {
    document.location.href = '/';
  }

  if (to === from) {
    return;
  }

  if (! to.meta.middleware.includes(store.state.auth.user.role.toLowerCase())) {
    next('/');
  } else {
    next();
  }

  // const middleware = to.meta.middleware;
  //
  // const context = {
  //   to, from, next, store
  // };
  //
  // return middleware[0]({
  //   ...context,
  //   nextMiddleware: middlewarePipeline(context, middleware, 1)
  // });
});

export default router;