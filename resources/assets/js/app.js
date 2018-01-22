// Стили
require('./bootstrap');
// Vuetify
// import('./node_modules/vuetify/dist/vuetify.min.css');

// Зависимости
window.Vue = require('vue');
window.Vuetify=require('vuetify').default;
window.VueRouter=require('vue-router').default;
window.VueAxios=require('vue-axios').default;
window.Axios=require('axios').default;



let AppLayout= require('./components/ofers/App.vue');

// Шаблон вывода новосте
const Listposts=Vue.component('Listposts', require('./components/ofers/Listpost.vue'));
// Добавление постов
const Addpost =Vue.component('Addpost', require('./components/ofers/Addposts.vue'));
// Редактирование постов
const Editpost =Vue.component('Editpost', require('./components/ofers/Editpost.vue'));
// Удаление постов
const Deletepost =Vue.component('Deletepost', require('./components/ofers/Deletepost.vue'));
// Просмотр одной публикации
const Viewpost =Vue.component('Viewpost', require('./components/ofers/Viewpost.vue'));

Vue.component('layout', require('./components/Layout.vue'));
Vue.component('search', require('./components/ofers/Search.vue'));

// регистрирующие модули
Vue.use(VueRouter,VueAxios, axios, Vuetify);
// Vue.use(VueRouter,VueAxios, axios);

// Роуты
const routes = [
    {
        name: 'Listposts',
        path: '/',
        component: Listposts
    },
    {
        name: 'Addpost',
        path: '/add-post',
        component: Addpost
    },
    {
        name: 'Editpost',
        path: '/edit/:id',
        component: Editpost
    },
    {
        name: 'Deletepost',
        path: '/post-delete',
        component: Deletepost
    },
    {
        name: 'Viewpost',
        path: '/view/:id',
        component: Viewpost
    }
];


const router = new VueRouter({ mode: 'history', routes: routes});

// new Vue(
//     Vue.util.extend(
//         { router },
//         AppLayout
//     )
// ).$mount('#app');

// Vue.use((Vue) => {
//     Vue.prototype.$http = window.axios
// });

new Vue({
    el: '#app'
});

// const app = new Vue({
//     el: '#app'
// });

window.appVue = {
    numberGoods : 0,
    products : {}
};

// корзина
// window.basket = {
//     number : 0,
//     products : {}
// };