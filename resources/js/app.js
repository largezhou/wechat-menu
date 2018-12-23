import Vue from 'vue';

// let token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// }

Vue.component('menus', require('./pages/Menus'));

new Vue({
    el: '#wechat-menu',
});
