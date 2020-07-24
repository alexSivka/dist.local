/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//const files = require.context('./', true, /\.vue$/i);
//files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('BookList', require('./components/BookList.vue').default);

import BookList from './components/BookList';
import BookEdit from './components/BookEdit';
import BookView from './components/BookView';
import TagList from './components/TagList';
import TagEdit from './components/TagEdit';



const  PageNotFound = {
    template: '<p>Страница не найдена</p>'
};


// в проде роутер в отдельном файле.
const routes = [
    { path: '/', component: BookList },
    { path: '/page-:page', component: BookList },
    { path: '/edit', component: BookEdit },
    { path: '/edit/:id', component: BookEdit },
    { path: '/book/:id', component: BookView },
    { path: '/tags', component: TagList},
    { path: '/tag', component: TagEdit },
    { path: '/tag/:id', component: TagEdit },
    { path: "*", component: PageNotFound }
];

import VueRouter from 'vue-router';
const router = new VueRouter({
    mode: 'history',
    routes
});



Vue.use(VueRouter);



const app = new Vue({
    router,
    el: '#app',
});
