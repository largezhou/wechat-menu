import Vue from 'vue'
import noticeBar from '@/common/notice-bar'
import Vuelidate from 'vuelidate'

Vue.use(noticeBar)
Vue.use(Vuelidate)

if (process.env.NODE_ENV == 'development') {
    window.log = console.log.bind(console)
    window.Vue = Vue
} else {
    window.log = () => {}
}

Vue.component('menus-editor', require('@/components/MenusEditor'))
Vue.component('menu-events-setting', require('@/components/MenuEventsSetting'))
Vue.component('other-events-setting', require('@/components/OtherEventsSetting'))

// 传递事件
const $bus = new Vue
Vue.prototype.$bus = $bus
Vue.$bus = $bus

// 全局数据
const $global = new Vue({
    data() {
        return {
            currentMenu: null,
            currentMenuIndex: null,
        }
    },
})
Vue.prototype.$global = $global
Vue.$global = $global

new Vue({
    el: '#wechat-menu',
})
