/**
 * 一级菜单最大数
 * @type {number}
 */
export const MAX_COLUMN = 3

/**
 * 二级菜单最大数
 * @type {number}
 */
export const MAX_SUB_COUNT = 5

export const MENU_TYPES = {
    click: '点击',
    view: '链接',
    scancode_push: '扫码推',
    scancode_waitmsg: '扫码推提示框',
    pic_sysphoto: '拍照发图',
    pic_photo_or_album: '拍照或相册发图',
    pic_weixin: '相册发图',
    location_select: '地理位置',
    miniprogram: '小程序',
}

export const TYPES_TEXT = {
    msg: '自动回复',
    callback: '事件处理',
}

/**
 * 菜单的样式高度
 * @type {number}
 */
export const MENU_HEIGHT = 50

/**
 * 子菜单块的 top 样式的偏移量
 * @type {number}
 */
export const SUB_MENUS_OFFSET = 10

/**
 * 微信开发错误码大全
 * @type {string}
 */
export const WECHAT_ERROR_CODES = 'https://mp.weixin.qq.com/wiki?action=doc&id=mp1433747234'

/**
 * 非自定义菜单点击事件
 *
 * @type {Object}
 */
export const OTHER_EVENT_TYPES = {
    text: '文本',
    image: '图片',
    location: '位置',
    link: '链接',
    video: '视频',
    subscribe: '订阅',
    unsubscribe: '取消订阅',
}

/**
 * 页面选项卡
 *
 * @type {Object}
 */
export const PAGES = {
    'menus-editor': '菜单设置',
    'menu-events-setting': '菜单事件配置',
    'other-events-setting': '其他事件配置',
}

/**
 * 自动回复的消息类型
 *
 * @type {Object}
 */
export const AUTO_REPLY_TYPES = {
    text: '文本',
    news: '图文',
    image: '图片',
    voice: '音频',
    video: '视频',
}
