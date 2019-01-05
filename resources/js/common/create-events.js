import { saveSettings } from '@/api/wechat'

export default {
    methods: {
        /**
         * 保存事件配置
         *
         * @param $v vuelidate 的 $v 对象
         * @param events 要保存的数据
         * @param key 保存到的键值
         * @returns {Promise<void>}
         */
        async onSave($v, events, key) {
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

            try {
                this.saving = true
                return await saveSettings(key, events)
            } finally {
                this.saving = false
            }
        },
    },
}
