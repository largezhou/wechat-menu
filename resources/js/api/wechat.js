import axios from 'axios'

const bassURLEl = document.querySelector('#wechat-menu-prefix')
const baseURL = bassURLEl && bassURLEl.getAttribute('data-prefix')
axios.defaults.baseURL = baseURL ? baseURL : 'wechat-menu'

export function getMenus() {
    return axios.get('/menus')
}

export function createMenus(data) {
    return axios.post('/menus', {
        data,
    })
}

export function getEvents() {
    return axios.get('/events')
}

export function createEvents(data) {
    return axios.post('/events', {
        data,
    })
}
