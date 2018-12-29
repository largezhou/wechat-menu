import Vue from 'vue'

window.log = console.log.bind(console)

Vue.component('menus-editor', require('@/pages/MenusEditor'))
Vue.component('events-setting', require('@/pages/EventsSetting'))

// 传递事件
Vue.prototype.$bus = new Vue

// 全局数据
Vue.prototype.$global = new Vue({
    data() {
        return {
            currentMenu: null,
        }
    },
})

new Vue({
    el: '#wechat-menu',
})
