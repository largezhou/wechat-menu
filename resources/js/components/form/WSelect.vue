<template>
    <form-item
        v-bind="_formItemProps"
        class="w-select"
    >
        <select
            :value="value"
            @input="$emit('input', $event.target.value)"
            class="input"
        >
            <slot/>
        </select>
    </form-item>
</template>

<script>
import FormItemHelper from '@/common/form-item'

export default {
    name: 'WSelect',
    mixins: [
        FormItemHelper,
    ],
    props: {
        value: [String, Number],
    },
}
</script>
