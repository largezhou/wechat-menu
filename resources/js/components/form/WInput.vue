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
import formItemHelper from '@/common/form-item'

export default {
    name: 'WInput',
    mixins: [
        formItemHelper,
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
