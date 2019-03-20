<template>
    <label
        class="wm-radio-input wm-cursor-pointer"
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

<style lang="scss" scoped>
.wm-radio-input {
    display: initial;
    max-width: initial;
    margin-bottom: initial;
    font-weight: initial;
}
</style>
