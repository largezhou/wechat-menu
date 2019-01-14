<template>
    <transition
        name="mask-in"
        @after-leave="onAfterLeave"
    >
        <div
            v-show="show"
            class="dialog-mask"
            :class="[{ disabled: persistent }, styleClass]"
            @click.self="onClickMask"
        >
            <div
                class="dialog"
            >
                <div
                    v-if="title"
                    class="header"
                >
                    {{ title }}
                    <span
                        class="header-close"
                        @click="onCancel"
                    >X</span>
                </div>
                <div
                    class="content"
                    :style="styles"
                >
                    <slot>
                        <template v-if="isText">
                            {{ content }}
                        </template>
                        <div
                            v-else-if="isHtml"
                            v-html="content"
                        />
                        <render-content
                            v-else
                            :h="content"
                        />
                    </slot>
                </div>
                <div
                    class="footer"
                >
                    <button
                        v-for="(b, index) of buttons"
                        :class="b.class"
                        @click="onBtnClick(index)"
                        v-text="b.text"
                    />
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import RenderContent from '@/components/RenderContent'

export default {
    name: 'WDialog',
    components: {
        RenderContent,
    },
    data() {
        return {
            show: false,
        }
    },
    props: {
        styleClass: String,
        width: {
            type: String,
            default: '500px',
        },
        height: {
            type: String,
            default: 'auto',
        },
        title: {
            type: String,
            default: '提示',
        },
        content: {
            type: [String, Function],
            default: '',
        },
        // 是否把字符串类型的 content 输出为 html
        html: Boolean,
        // 点击遮罩层是否可关闭，true 不可关闭，false 可关闭
        persistent: Boolean,
        // 底部按钮，如果有 callback ，则会调用 callback，否则会触发 buttonClicked 事件
        buttons: {
            type: Array,
            default() {
                return [
                    {
                        class: 'btn btn-primary',
                        text: '确定',
                    },
                    {
                        class: 'btn',
                        text: '取消',
                        callback: () => this.onCancel(),
                    },
                ]
            },
        },
        callback: Function,
    },
    mounted() {
        this.$nextTick(() => {
            this.show = true
        })
    },
    computed: {
        styles() {
            return {
                width: this.width,
                height: this.height,
            }
        },
        isHtml() {
            return this.html
                && (typeof this.content == 'string')
        },
        isText() {
            return !this.html
                && (typeof this.content == 'string')
        },
    },
    methods: {
        onCancel() {
            this.show = false
            this.$emit('onCancel')
        },
        onClickMask(e) {
            if (!this.persistent) {
                return this.onCancel(e)
            }
        },
        onBtnClick(index) {
            const callback = this.buttons[index].callback
            if (typeof callback == 'function') {
                callback(this)
                return
            }

            const values = this.buttons.map((b, i) => {
                return index == i
            })

            this.$emit('buttonClicked', [this].concat(values))
            if (typeof this.callback == 'function') {
                this.callback(this, ...values)
            }
        },
        onAfterLeave() {},
        close() {
            this.show = false
        },
    },
}
</script>

<style scoped lang="scss">
@import '~@/../sass/vars';

.dialog-mask {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 100;

    &.disabled {
        cursor: not-allowed !important;

        .dialog * {
            cursor: initial;
        }
    }
}

.dialog {
    position: fixed;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fff;
    border-radius: 3px;
}

.header {
    border-bottom: 2px solid $grey;
    padding: 20px 20px 10px 20px;
    font-size: 18px !important;
}

.content {
    padding: 20px;
    overflow: auto;
}

.footer {
    padding: 20px;
    text-align: right;
}

.header-close {
    float: right;
    padding: 0 10px 0 10px;
    cursor: pointer !important;
    font-weight: 600;
    color: $grey-1;
}

.mask-in-enter-active,
.mask-in-leave-active {
    transition: all .2s;
}

.mask-in-enter,
.mask-in-leave-to {
    opacity: 0;
}
</style>
