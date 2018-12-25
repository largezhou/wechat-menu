import Vue from 'vue'

window.log = console.log.bind(console)

Vue.component('menu-manager', require('@/pages/MenuManager'))

new Vue({
    el: '#wechat-menu',
})
