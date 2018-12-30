import NoticeBar from '@/components/NoticeBar'

const notice = options => {
    let propsData

    if (typeof options == 'object') {
        propsData = options
    } else {
        propsData = {
            msg: options,
        }
    }

    const ins = new NoticeBar({
        propsData,
    })

    document.body
        .querySelector('#wechat-menu')
        .appendChild(ins.$mount().$el)

    return ins
}

export default {
    install(Vue) {
        Vue.$notice = notice
        Vue.prototype.$notice = notice
    },
}
