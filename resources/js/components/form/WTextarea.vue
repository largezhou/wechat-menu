<template>
    <form-item
        v-bind="_formItemProps"
    >
        <textarea
            :value="value"
            :rows="rows"
            :cols="cols"
            @input="$emit('input', $event.target.value)"
            class="input"
            ref="textarea"
        />
    </form-item>
</template>

<script>
import FormItemHelper from '@/common/form-item'

export default {
    name: 'WTextarea',
    mixins: [
        FormItemHelper,
    ],
    props: {
        value: [String, Number],
        rows: [String, Number],
        cols: [String, Number],
    },
    methods: {
        focus() {
            this.$refs.textarea.focus()
        },
    },
}
</script>
