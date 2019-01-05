<template>
    <form-item
        v-bind="_formItemProps"
    >
        <input
            :value="value"
            @input="$emit('input', $event.target.value)"
            :type="type"
            class="input"
        >
    </form-item>
</template>

<script>
import FormItemHelper from '@/common/form-item'

export default {
    name: 'WInput',
    mixins: [
        FormItemHelper,
    ],
    props: {
        value: [String, Number],
        type: {
            type: String,
            default: 'text',
        },
    },
}
</script>
