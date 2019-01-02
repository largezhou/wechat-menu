import Vue from 'vue'
import noticeBar from '@/common/notice-bar'

Vue.use(noticeBar)

window.log = console.log.bind(console)
window.Vue = Vue

Vue.component('menus-editor', require('@/components/MenusEditor'))
Vue.component('menu-events-setting', require('@/components/MenuEventsSetting'))

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
