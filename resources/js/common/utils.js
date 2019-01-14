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
