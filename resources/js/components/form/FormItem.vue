<template>
    <div
        class="wm-form-item"
        :class="classes"
    >
        <span v-show="label" class="wm-label">{{ label }}</span>
        <div class="wm-content">
            <slot/>
            <span
                v-show="errorText"
                class="wm-error-text"
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
                'wm-inline': this.inline,
                'wm-has-error': this.hasError,
                'wm-error-inside': this.errorInside,
            }
        },
    },
}
</script>

<style scoped>
.wm-label {
    width: 60px;
    text-align: right;
}
</style>
