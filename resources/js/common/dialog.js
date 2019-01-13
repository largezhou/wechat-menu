import WDialog from '@/components/WDialog'
import Vue from 'vue'

const DialogComp = Vue.extend(WDialog)

const dialog = (options) => {
    let propsData

    if (typeof options == 'object') {
        propsData = options
    } else {
        propsData = {
            content: options,
        }
    }

    const ins = new DialogComp({
        propsData,
        methods: {
            onAfterLeave() {
                this.$destroy()
                this.$el.parentElement.removeChild(this.$el)
            },
        },
    })

    document.body
        .querySelector('#wechat-menu')
        .appendChild(ins.$mount().$el)

    return ins
}

export default {
    install(Vue) {
        Vue.$dialog = dialog
        Vue.prototype.$dialog = dialog
    },
}
