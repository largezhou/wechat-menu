import Vue from 'vue'
import VueDND from 'awe-dnd'

window.log = console.log.bind(console)

Vue.use(VueDND)

Vue.component('menu-manager', require('@/pages/MenuManager'))

new Vue({
    el: '#wechat-menu',
})
