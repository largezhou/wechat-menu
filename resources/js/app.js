import Vue from 'vue'

window.log = console.log.bind(console)

Vue.component('menus', require('./pages/Menus'))

new Vue({
    el: '#wechat-menu',
})
