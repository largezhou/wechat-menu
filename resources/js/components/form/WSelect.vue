<template>
    <form-item
        v-bind="_formItemProps"
        class="wm-w-select"
    >
        <select
            :value="value"
            @input="$emit('input', $event.target.value)"
            class="wm-input"
            ref="input"
        >
            <slot>
                <option
                    ref="options"
                    v-for="item of options"
                    :key="item[valueField]"
                    :value="item[valueField]"
                >{{ item[textField] }}</option>
            </slot>
        </select>
    </form-item>
</template>

<script>
import formItemHelper from '@/common/form-item'

export default {
    name: 'WSelect',
    mixins: [
        formItemHelper,
    ],
    props: {
        value: [String, Number],
        options: {
            type: Array,
            default: () => [],
        },
        valueField: {
            type: String,
        },
        textField: {
            type: String,
        },
    },
    watch: {
        // 动态加载进来的 options，如果 select 先有值，则不会选中应该选中的，所以这样处理一下
        options() {
            this.$nextTick(() => {
                const matched = this.$refs.options.some(o => {
                    const res = o.value == this.value
                    return o.selected = res
                })

                if (!matched) {
                    this.$refs.input.selectedIndex = -1
                }
            })
        },
    },
}
</script>
