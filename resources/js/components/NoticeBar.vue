<template>
    <transition
        name="in"
        @after-leave="onAfterLeave"
    >
        <div
            v-show="show"
            class="wm-notice-bar"
            :class="['wm-' + type]"
        >
            <render-content
                v-if="typeof msg == 'function'"
                :h="msg"
            />
            <template v-else>{{ msg }}</template>
        </div>
    </transition>
</template>

<script>
import Vue from 'vue'
import RenderContent from '@/components/RenderContent'

export default Vue.extend({
    name: 'NoticeBar',
    components: {
        RenderContent,
    },
    data() {
        return {
            show: false,
        }
    },
    props: {
        type: String,
        msg: [String, Function, Number],
        duration: {
            type: Number,
            default: 4000,
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.show = true
        })

        setTimeout(() => {
            this.show = false
        }, this.duration)
    },
    methods: {
        onAfterLeave() {
            this.$destroy()
            this.$el.parentElement.removeChild(this.$el)
        },
    },
})
</script>

<style scoped lang="scss">
@import "~@/../sass/vars";

.wm-notice-bar {
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 3px;
    background: $grey;
    padding: 15px 20px;
    color: $grey-1;
    z-index: 1988;
    width: 350px;
    word-break: break-all;

    &.wm-success {
        background-color: #44b549;
        color: #fff;
    }

    &.wm-error {
        background-color: #f76f73;
        color: #fff;
        border-color: #e1f3d8;
    }
}

.wm-in-enter-active,
.wm-in-leave-active {
    transition: all .5s;
}

.wm-in-enter,
.wm-in-leave-to {
    top: 0;
    opacity: 0;
}
</style>
