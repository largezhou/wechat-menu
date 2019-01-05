<template>
    <form-item
        v-bind="_formItemProps"
        class="radio-item"
        ref="formItem"
    >
        <div
            class="radio-group"
        >
            <slot ref="items"/>
        </div>
    </form-item>
</template>

<script>
import FormItemHelper from '@/common/form-item'

export default {
    name: 'WRadio',
    mixins: [
        FormItemHelper,
    ],
    props: {
        value: [String, Number],
    },
    mounted() {
        this.$refs.formItem.$on('input', this.onInput)
    },
    beforeDestroy() {
        this.$refs.formItem.$off('input', this.onInput)
    },
    methods: {
        onInput(e) {
            this.$emit('input', e)
        },
    },
    watch: {
        value: {
            handler(newValue) {
                this.$slots.default.some(i => {
                    this.$nextTick(() => {
                        const ins = i.componentInstance
                        ins.check(ins.value === newValue)
                    })
                })
            },
            immediate: true,
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.radio-group {
    padding: 0 5px;
    line-height: 30px;
    min-height: 30px;
    border: 1px solid transparent;

    &.has-error {
        border: 1px solid $danger-color;
        border-radius: 3px;
    }
}
</style>
