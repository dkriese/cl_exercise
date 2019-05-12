/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('people-component', require('./components/PeopleComponent.vue').default);
if (process.env.MIX_ENV_MODE === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

const app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        title: 'test'
    }
});
