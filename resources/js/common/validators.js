/**
 * 验证是不是 Path\To\Class@handle 类型的值
 *
 * @param value 当前字段值
 * @param data 整个数据对象
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
