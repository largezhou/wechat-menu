<template>
    <form-item
        v-bind="_formItemProps"
    >
        <a
            href="javascript:void(0);"
            title="查看"
        >{{ content }}</a>
        <a
            href="javascript:void(0);"
            style="float: right;"
            @click="onPickMaterial"
        >选择素材</a>
    </form-item>
</template>

<script>
import FormItemHelper from '@/common/form-item'
import { AUTO_REPLY_TYPES } from '@/common/constants'
import { objGet } from '@/common/utils'

export default {
    name: 'MediaPicker',
    mixins: [
        FormItemHelper,
    ],
    data() {
        return {
            newValue: null,
        }
    },
    props: {
        value: {
            type: Object,
            default: () => {},
        },
    },
    computed: {
        content() {
            const val = this.value

            if (!val) {
                return '还未选择素材'
            }

            if (val.type == 'text') {
                return val.value || '点击设置文字内容'
            }

            return `已选择${AUTO_REPLY_TYPES[val.type]}`
        },
    },

    methods: {
        onPickMaterial() {
            this.$dialog({
                styleClass: 'media-picker',
                title: '选择素材',
                width: '700px',
                height: '450px',
                persistent: true,
                buttons: [
                    {
                        class: 'btn btn-primary',
                        text: '确定',
                    },
                    {
                        class: 'btn',
                        text: '取消',
                    },
                    {
                        class: 'btn',
                        text: '清除',
                    },
                ],
                content: (h) => {
                    const props = {
                        initType: objGet(this.value, 'type'),
                        value: objGet(this.value, 'value'),
                    }

                    return h('media-browser', {
                        props,
                        on: {
                            input: (value) => {
                                this.newValue = value
                            },
                        },
                    })
                },
                callback: (dialog, ok, cancel, clear) => {
                    if (ok) {
                        this.$emit('input', this.newValue)
                    }

                    if (clear) {
                        this.$emit('input', null)
                    }

                    dialog.close()
                },
            })
        },
    },
}
</script>

<style scoped lang="scss">
</style>
