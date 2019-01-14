import axios from 'axios'
import Vue from 'vue'
import { WECHAT_ERROR_CODES } from '@/common/constants'

const bassURLEl = document.querySelector('#wechat-menu-prefix')
const baseURL = bassURLEl && bassURLEl.getAttribute('data-prefix')
axios.defaults.baseURL = baseURL || '/wechat-menu'

axios.interceptors.response.use(
    res => {
        let msg = res.data.msg
        const status = res.data.status

        if (msg) {
            if (!status) {
                const errorType = res.data.type
                // 微信错误，显示一个查看详情的链接
                if (errorType == 'wechat') {
                    const t = msg
                    msg = (h) => {
                        return h(
                            'div',
                            [
                                h('span', t),
                                h('a', {
                                    attrs: {
                                        href: WECHAT_ERROR_CODES,
                                        target: '_blank',
                                    },
                                    style: {
                                        marginLeft: '10px',
                                    },
                                }, '查看详情'),
                            ],
                        )
                    }
                }
            }

            Vue.$notice({
                msg,
                type: status ? 'success' : 'error',
            })
        }

        return res
    },
    err => {
        const res = err.response
        let msg

        if (!res) {
            msg = '请求出错'
        } else {
            switch (res.status) {
                case 404:
                    msg = '请求的网址的不存在'
                    break
                default:
                    msg = `服务器错误(code: ${res.status})`
            }
        }

        Vue.$notice({
            msg,
            type: 'error',
        })

        return Promise.reject(err)
    },
)

export function getResources(type, params = {}) {
    return axios.get('/resources', {
        params: {
            type,
            ...params,
        },
    })
}

export function postResources(type, data) {
    return axios.post('/resources', {
        type,
        data,
    })
}
