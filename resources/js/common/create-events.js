import { createEvents } from '@/api/wechat'

export default {
    methods: {
        async onSave(events) {
            if (this.saving) {
                return
            }

            if (events instanceof Event) {
                events = this.events
            }

            const valid = this.valid(events)
            if (valid !== true) {
                this.$notice({
                    msg: valid,
                    type: 'error',
                })
                return
            }

            try {
                this.saving = true
                return await createEvents(events)
            } finally {
                this.saving = false
            }
        },
        valid(events) {
            const keys = []
            const remarks = []
            let errorMsg

            events.every((e, index) => {
                const prefix = `第 ${index + 1} 个配置的`

                if (!e.remark) {
                    errorMsg = prefix + '备注不能为空'
                    return false
                }

                if (remarks.indexOf(e.remark) !== -1) {
                    errorMsg = prefix + '备注不能重复'
                    return false
                }

                if (!e.key) {
                    errorMsg = prefix + '事件标识不能为空'
                    return false
                }

                if (keys.indexOf(e.key) !== -1) {
                    errorMsg = prefix + '事件标识不能重复'
                    return false
                }

                if (!e.content) {
                    errorMsg = prefix + '内容不能为空'
                    return false
                }

                if (e.type == 'callback') {
                    const [className, methodName] = e.content.split('@')

                    if (!className || !methodName) {
                        errorMsg = prefix + '内容格式不对'
                        return false
                    }
                }

                keys.push(e.key)
                remarks.push(e.remark)

                return true
            })

            return errorMsg || true
        },
    },
}
