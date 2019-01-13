import Vue from 'vue'
import noticeBar from '@/common/notice-bar'
import Vuelidate from 'vuelidate'
import '@/common/global-components'
import Dialog from '@/common/dialog'

Vue.use(noticeBar)
Vue.use(Vuelidate)
Vue.use(Dialog)

if (process.env.NODE_ENV == 'development') {
    window.log = console.log.bind(console)
    window.Vue = Vue
} else {
    window.log = () => {}
}

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
            news: [],
            images: [],
            voices: [],
            videos: [],
        }
    },
})
Vue.prototype.$global = $global
Vue.$global = $global

new Vue({
    el: '#wechat-menu',
})
