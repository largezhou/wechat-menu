<template>
    <label
        class="wm-cursor-pointer"
    >
        <input
            type="radio"
            :value="value"
            @input="$parent.$emit('input', $event.target.value)"
            ref="input"
        >
        {{ label }}
    </label>
</template>

<script>
export default {
    name: 'WRadioItem',
    props: {
        label: String,
        name: String,
        value: [String, Number],
    },
    methods: {
        check(val) {
            this.$refs.input.checked = val
        },
    },
}
</script>
