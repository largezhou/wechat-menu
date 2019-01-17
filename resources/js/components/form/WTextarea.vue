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
            :readonly="readonly"
        />
    </form-item>
</template>

<script>
import formItemHelper from '@/common/form-item'

export default {
    name: 'WTextarea',
    mixins: [
        formItemHelper,
    ],
    props: {
        value: [String, Number],
        rows: [String, Number],
        cols: [String, Number],
        readonly: Boolean,
    },
    methods: {
        focus() {
            this.$refs.textarea.focus()
        },
    },
}
</script>
