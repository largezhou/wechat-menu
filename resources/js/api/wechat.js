import axios from 'axios'
import Vue from 'vue'

const bassURLEl = document.querySelector('#wechat-menu-prefix')
const baseURL = bassURLEl && bassURLEl.getAttribute('data-prefix')
axios.defaults.baseURL = baseURL || 'wechat-menu'

axios.interceptors.response.use(
    res => {
        const config = res.config

        const msg = res.data.msg
        const status = res.data.status

        if (msg) {
            if (status && config.noErrorNotice) {
                Vue.$notice({
                    msg,
                    type: 'success',
                })
            } else if (!config.noErrorNotice) {
                Vue.$notice({
                    msg,
                    type: status ? 'success' : 'error',
                })
            }
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

export function getMenus() {
    return axios.get('/menus')
}

export function createMenus(data) {
    return axios.post(
        '/menus',
        {
            data,
        },
        {
            noErrorNotice: true,
        },
    )
}

export function getMenuEvents() {
    return axios.get('/menu-events')
}

export function createEvents(data) {
    return axios.post('/menu-events', {
        data,
    })
}
