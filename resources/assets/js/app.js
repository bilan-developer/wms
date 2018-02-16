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
        time: 1000,
        color: 'error'
    }
    ,
    actions: {
        errorBlock({commit}, data) {
            commit('ERROR_BLOCK', data)
        },
        successBlock({commit}, data) {
            commit('SUCCESS_BLOCK',data)
        }
    },
    mutations: {
        ERROR_BLOCK(state, data) {
            state.time = data.time;
            state.text = data.text;
            state.color = 'error';
            state.status = true;
        },
        SUCCESS_BLOCK(state, data) {
            state.time = data.time;
            state.text = data.text;
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
const user =  {
    state: {
        user: {
            name: '',
            id_role: 1
        }
    },
    actions: {
        initUser({commit}) {
            commit('INIT_USER')
        }
    },
    mutations: {
        INIT_USER(state) {
            Axios.get('/get-user')
                .then((response) => {
                    state.user = response.data;
                });
        }
    },
    getters: {
        user(state){ return state.user },
        isGuest(state){ return state.user.id_role != 3;}
    }
};
const categories =  {
    state: {
        categories: []
    },
    actions: {
        getCategories({commit}) {
            commit('GET_CATEGORIES')
        }
    },
    mutations: {
        GET_CATEGORIES(state) {
            Axios.get('/category')
                .then((response) => {
                    state.categories = response.data;
                });
        }
    },
    getters: {
        categories(state){
            return state.categories
        }
    }
};


const store = new Vuex.Store({
    modules: {
        toasts: toasts,
        basket: basket,
        user  : user,
        categories: categories
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