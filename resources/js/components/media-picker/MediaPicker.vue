<template>
    <form-item
        v-bind="_formItemProps"
        class="wm-media-picker"
    >
        <a
            href="javascript:void(0);"
            style="float: right;"
            @click="onPickMaterial"
        >选择素材</a>
        <div
            v-if="type == 'text'"
            class="wm-text"
        >
            <w-textarea
                v-model="value.value"
            />
        </div>
        <a
            v-else
            :href="viewLink(item, type)"
            target="_blank"
            @click.stop="onView(item, type)"
            title="查看"
        >
            <loading-dots
                :loading="videoLinkLoading"
                :num="20"
                :text="content"
            />
        </a>
    </form-item>
</template>

<script>
import formItemHelper from '@/common/form-item'
import { AUTO_REPLY_TYPES } from '@/common/constants'
import { objGet } from '@/common/utils'
import viewMedia from '@/common/view-media'

export default {
    name: 'MediaPicker',
    mixins: [
        formItemHelper,
        viewMedia,
    ],
    data() {
        return {
            newValue: null,
        }
    },
    props: ['value'],
    created() {
        if (typeof this.value != 'object') {
            this.$emit('input', null)
        }
    },
    computed: {
        item() {
            return objGet(this.value, 'value')
        },
        type() {
            return objGet(this.value, 'type')
        },
        content() {
            if (!AUTO_REPLY_TYPES[this.type]) {
                return '还未选择素材'
            }

            if (this.type == 'text') {
                return this.item || '请设置文字内容'
            } else {
                return (this.item ? '已选择' : '请选择') + AUTO_REPLY_TYPES[this.type]
            }
        },
    },

    methods: {
        onPickMaterial() {
            this.$dialog({
                styleClass: 'wm-media-picker',
                title: '选择素材',
                width: '700px',
                height: '450px',
                persistent: true,
                buttons: [
                    {
                        class: 'wm-btn wm-btn-primary',
                        text: '确定',
                    },
                    {
                        class: 'wm-btn',
                        text: '取消',
                    },
                    {
                        class: 'wm-btn wm-btn-danger',
                        text: '清除',
                    },
                ],
                content: (h) => {
                    let initType = objGet(this.value, 'type')
                    let value = objGet(this.value, 'value')

                    // 如果类型不对，则给个默认类型，并把值清除
                    if (!AUTO_REPLY_TYPES[initType]) {
                        initType = 'text'
                        value = null
                    }

                    return h('media-browser', {
                        props: {
                            initType,
                            value,
                        },
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
.wm-text {
    width: calc(100% - 100px);
}
</style>
