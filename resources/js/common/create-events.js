import { saveSettings } from '@/api/wechat'

export default {
    methods: {
        async onSave($v, events) {
            if (
                ($v instanceof Event)
                || $v === undefined
            ) {
                throw '必须传入一个 vuelidate 插件的 $v 对象'
            }

            if (this.saving) {
                return
            }

            $v.$touch()
            if ($v.$invalid) {
                this.$notice({
                    msg: '请填写完正确的配置',
                    type: 'error',
                })
                return
            }

            events = events || $v.events.$model

            try {
                this.saving = true
                return await saveSettings('menu_events', events)
            } finally {
                this.saving = false
            }
        },
    },
}
