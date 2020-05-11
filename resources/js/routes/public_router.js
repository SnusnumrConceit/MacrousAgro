/** Dashboard Routes **/
import VueRouter from 'vue-router';

import Landing from '../components/dashboard/Landing';
import Cabinet from '../components/dashboard/Cabinet';

import CategoryProducts from '../components/dashboard/product/Products';
import ProductDetail from '../components/dashboard/product/ProductDetail';

import DashboardPhotos from '../components/dashboard/Photos';
import DashboardVideos from '../components/dashboard/Videos';

import DashboardArticles from '../components/dashboard/articles/Articles';
import DashboardArticle from '../components/dashboard/articles/Article';

import {store} from "../store/store";

// import customer from '../middleware/customer';
import middlewarePipeline from '../middleware/middlewarePipeline';

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: Landing,
    beforeEnter: null
  },
  /** Articles routes **/
  {
    path: '/articles',
    name: 'DashboardArticles',
    component: DashboardArticles,
    meta: {
      middleware: null
    }
  },
  {
    path: '/articles/:id',
    name: 'DashboardArticle',
    component: DashboardArticle,
    meta: {
      middleware: null
    }
  },

  /** Cabinet routes **/
  {
    path: '/cabinet',
    name: 'Cabinet',
    component: Cabinet,
    meta: {
      middleware: [
        'customer'
      ]
    },
  },

  /** Categories routes **/
  {
    path: '/categories/:category_id/products',
    name: 'CategoryProducts',
    component: CategoryProducts,
    meta: {
      middleware: null
    }
  },

  /** Photos routes **/
  {
    path: '/photos',
    name: 'DashboardPhotos',
    component: DashboardPhotos,
    meta: {
      middleware: null
    }
  },

  /** Products routes **/
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: ProductDetail,
    meta: {
      middleware: null
    }
  },

  /** Videos routes **/
  {
    path: '/videos',
    name: 'DashboardVideos',
    component: DashboardVideos,
    meta: {
      middleware: null
    }
  }
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes
});

/** Middleware Guard **/
router.beforeEach((to, from, next) => {
  if (!to.meta.middleware) {
    return next();
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
