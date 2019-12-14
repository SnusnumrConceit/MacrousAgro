import Categories from './components/admin/categories/index';
import CategoryForm from './components/admin/categories/form';

import News from './components/admin/news/index';
import NewsForm from './components/admin/news/form';

import Photos from './components/admin/photos/index';
import PhotoForm from './components/admin/photos/form';

import Videos from './components/admin/videos/index';
import VideoForm from './components/admin/videos/form';

import Users from './components/admin/users/index';
import UserForm from './components/admin/users/form';

export const routes = [
    {
        path: '/admin/categories',
        name: 'Categories',
        component: Categories,
        beforeEnter: null
    },
    {
        path: '/admin/categories/:id',
        name: 'CategoryForm',
        component: CategoryForm,
        beforeEnter: null
    },

    {
        path: '/admin/news',
        name: 'News',
        component: News,
        beforeEnter: null
    },
    {
        path: '/admin/news/:id',
        name: 'NewsForm',
        component: NewsForm,
        beforeEnter: null
    },

    {
        path: '/admin/photos',
        name: 'Photos',
        component: Photos,
        beforeEnter: null
    },
    {
        path: '/admin/photos/:id',
        name: 'PhotoForm',
        component: PhotoForm,
        beforeEnter: null
    },

    {
        path: '/admin/videos',
        name: 'Videos',
        component: Videos,
        beforeEnter: null
    },
    {
        path: '/admin/videos/:id',
        name: 'VideoForm',
        component: VideoForm,
        beforeEnter: null
    },

    {
        path: '/admin/users',
        name: 'Users',
        component: Users,
        beforeEnter: null
    },
    {
        path: '/admin/users/:id',
        name: 'UserForm',
        component: UserForm,
        beforeEnter: null
    },
];
