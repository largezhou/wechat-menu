<template>
    <transition
        name="mask-in"
    >
        <div
            v-show="show"
            class="dialog-mask"
            @click.self="onClickMask"
        >
            <div
                class="dialog"
                :style="styles"
            >
                <div
                    v-if="title"
                    class="header"
                >
                    {{ title }}
                    <span
                        class="header-close"
                        @click="onHide"
                    >X</span>
                </div>
                <div class="content">
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
                </div>
                <div
                    class="footer"
                >
                    <template v-if="!buttons">
                        <button
                            class="btn btn-primary"
                            @click="$emit('onOk')"
                        >确定</button>
                        <button
                            class="btn"
                            @click="onHide"
                        >取消</button>
                    </template>
                    <template v-else>
                        <button
                            v-for="(b, index) of buttons"
                            :class="b.class"
                            @click="getBtnCallback(index)"
                            v-text="b.text"
                        />
                    </template>
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
        width: {
            type: String,
            default: '500px',
        },
        height: {
            type: String,
            default: 'auto',
        },
        title: String,
        content: {
            type: [String, Function],
            default: '',
        },
        html: Boolean,
        persistent: Boolean,
        buttons: Array,
        btnCallbacks: Array,
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
        onHide() {
            this.show = false
            this.$emit('onHide')
        },
        onClickMask(e) {
            if (!this.persistent) {
                return this.onHide(e)
            }
        },
        getBtnCallback(index) {
            const cb = this.btnCallbacks[index]

            if (typeof cb == 'function') {
                return cb
            } else {
                console.warn(`第 ${index + 1} 个按钮回调，必须是函数类型`)
                return () => {}
            }
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
}

.dialog {
    position: fixed;
    top: 30%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fff;
    border-radius: 3px;
}

.header {
    border-bottom: 2px solid $grey;
    padding: 20px 20px 10px 20px;
}

.content {
    padding: 20px;
}

.footer {
    padding: 20px;
    text-align: right;
}

.header-close {
    float: right;
    padding: 0 10px 0 10px;
    cursor: pointer;
    font-weight: 600;
    color: $grey-1;
}

.mask-in-enter-active,
.mask-in-leave-active {
    transition: all .1s;
}

.mask-in-enter,
.mask-in-leave-to {
    opacity: 0;
}
</style>
