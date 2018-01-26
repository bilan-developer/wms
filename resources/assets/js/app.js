// Стили
require('./bootstrap');

// Зависимости
window.Vue = require('vue');
window.Vuex = require('vuex');
window.Vuetify=require('vuetify').default;
window.VueRouter=require('vue-router').default;
window.VueAxios=require('vue-axios').default;
window.Axios=require('axios').default;

Vue.component('layout', require('./components/Layout.vue'));

// регистрирующие модули
Vue.use(VueRouter,VueAxios, axios, Vuetify, Vuex);

// Хранилище
const toasts =  {
    state: {
        status:false,
        text: "",
        time: 4000,
        color: 'error'
    }
    ,
    actions: {
        errorBlock({commit}, text, time) {
            commit('ERROR_BLOCK', text, time)
        },
        successBlock({commit}, text, time) {
            commit('SUCCESS_BLOCK', text, time)
        }
    },
    mutations: {
        ERROR_BLOCK(state, text, time) {
            state.time = time;
            state.text = text;
            state.color = 'error';
            state.status = true;
        },
        SUCCESS_BLOCK(state, text, time) {
            state.time = time;
            state.text = text;
            state.color = 'success';
            state.status = true;
        }
    }
};
const basket =  {
    state: {
        quantity: 0,
        products: []
    },
    actions: {
        addProduct({commit}, product) {
            commit('ADD_PRODUCTS', product)
        },
        plus({commit}, val) {
            commit('PLUS', val)
        },
        minus({commit}, val) {
            commit('MINUS', val)
        },
        clearBasket({commit}) {
            commit('CLEAR_BASKET')
        },
    },
    mutations: {
        ADD_PRODUCTS(state, product) {
            state.products.push(product)
        },
        PLUS(state, val) {
            state.quantity = state.quantity + val
        },
        MINUS(state, val) {
            state.quantity = state.quantity - val
        },
        CLEAR_BASKET(state) {
            state.quantity = 0;
            state.products = [];
        }
    },
    getters: {
        getProducts(state){
            return state.products
        },
        quantity(state){
            return state.quantity
        }
    }
};

const store = new Vuex.Store({
    modules: {
        toasts: toasts,
        basket: basket
    }
});

// Роуты
const routes = [

];
const router = new VueRouter({ mode: 'history', routes: routes});

new Vue({
    el: '#app',
    store
});

window.appVue = {
    numberGoods : 0,
    products : {}
};