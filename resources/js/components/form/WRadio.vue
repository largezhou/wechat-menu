<template>
    <form-item
        v-bind="_formItemProps"
        class="radio-item"
    >
        <div
            class="radio-group"
        >
            <slot/>
        </div>
    </form-item>
</template>

<script>
import FormItem from '@/components/form/FormItem'
import FormItemHelper from '@/common/form-item'

export default {
    name: 'WRadio',
    components: {
        FormItem,
    },
    mixins: [
        FormItemHelper,
    ],
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
