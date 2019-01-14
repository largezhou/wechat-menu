<template>
    <form-item
        v-bind="_formItemProps"
    >
        <a
            href="javascript:void(0);"
            title="点击选择素材"
            @click="onPickMaterial"
        >{{ content }}</a>
        <a
            v-show="value"
            href="javascript:void(0);"
            style="float: right;"
        >查看详情</a>
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
                return '点击选择素材'
            }

            if (val.type == 'text') {
                return val.text
            }

            return `已选择${AUTO_REPLY_TYPES[val.type]}`
        },
    },
    methods: {
        onPickMaterial() {
            this.$dialog({
                title: '选择素材',
                width: '750px',
                height: '450px',
                persistent: true,
                content: (h) => {
                    const props = {
                        initType: objGet(this.value, 'type'),
                        initText: objGet(this.value, 'text'),
                        value: this.newValue,
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
                callback: (dialog, ok, cancel) => {
                    if (ok) {
                        this.$emit('input', this.newValue)
                        dialog.close()
                    }
                },
            })
        },
    },
}
</script>

<style scoped lang="scss">
</style>
