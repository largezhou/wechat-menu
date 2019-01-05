<template>
    <div
        class="form-item"
        :class="classes"
    >
        <span v-show="label" class="label">{{ label }}</span>
        <div class="content">
            <slot/>
            <span
                v-show="errorText"
                class="error-text"
            >{{ errorText }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FormItem',
    props: {
        inline: Boolean,
        label: [String, Number],
        errorText: String,
        hasError: Boolean,
        errorInside: Boolean,
    },
    computed: {
        classes() {
            return {
                inline: this.inline,
                'has-error': this.hasError,
                'error-inside': this.errorInside,
            }
        },
    },
}
</script>

<style scoped>

</style>
