export const uniqueKey = () => {
    return Math.random().toString(32).substr(2)
}

/**
 * 按键获取对象元素
 *
 * @param obj
 * @param key
 * @param defaultVal
 * @returns {*}
 */
export const objGet = (obj, key, defaultVal = null) => {
    const keys = key.split('.')

    while (keys.length) {
        if (!obj || typeof obj != 'object') {
            return defaultVal
        }
        obj = obj[keys.shift()]
    }

    return obj || defaultVal
}

/**
 * 设置标签后，才能打开微信的图片，不然提示防盗链
 */
export const setHeadNoReferrer = () => {
    let referrerEl = document.querySelector('meta[name=referrer]')
    if (referrerEl) {
        referrerEl.content = 'never'
    } else {
        referrerEl = document.createElement('meta')
        referrerEl.name = 'referrer'
        referrerEl.content = 'never'
        document.head.prepend(referrerEl)
    }
}
