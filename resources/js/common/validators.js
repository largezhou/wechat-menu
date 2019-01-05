/**
 * 验证是不是 Path\To\Class@handle 类型的值
 *
 * @param value 当前字段值
 * @param data 整个数据对象
 *
 * @returns {boolean}
 */
export const callback = (value, data) => {
    if (!value) {
        return true
    }

    if (data.type == 'callback') {
        const t = value.split('@')

        if (t.length != 2) {
            return false
        }

        const [className, methodName] = t

        return !!(className && methodName)
    } else {
        return true
    }
}

/**
 * 验证是否重复
 *
 * 如果 param 中的值，会包含自己，就可能需要设置 excludeSelf 为 true，用来排除自己
 * 如果 param 中的值，是固定的，应该设置 excludeSelf 为 false
 *
 * @param param 存储所有值的数组
 * @param excludeSelf 是否排除自己
 *
 * @returns {function(*, *): boolean}
 */
export const unique = (param = [], excludeSelf = true) =>
    (value, data) => {
        const same = param.filter(p => p === value)

        return same.length <= (excludeSelf ? 1 : 0)
    }
