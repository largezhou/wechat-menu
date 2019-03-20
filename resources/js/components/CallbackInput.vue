<template>
    <div class="wm-callback-input wm-form-item" ref="callbackInput">
        <div class="wm-content">
            <input
                type="text"
                class="wm-input"
                :class="{ 'wm-has-error': hasError }"
                :value="value"
                ref="input"
                @focus="focused = true"
                @blur="focused = false"
                @input="$emit('input', $event.target.value)"
                @keydown.enter="onSelect"
            />
            <div
                v-show="show && filteredCallbacks.length"
                class="wm-autocomplete"
            >
                <template v-show="filteredCallbacks.length">
                    <div
                        class="wm-item wm-cursor-pointer"
                        v-for="(i, index) of filteredCallbacks"
                        :key="index"
                        @click="onSelect(i)"
                    >
                        {{ i }}
                    </div>
                </template>
            </div>
            <span class="wm-error-text">{{ errorText }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CallbackInput',
    data() {
        return {
            focused: false,
            show: false,
            callbacks: [],
        }
    },
    props: {
        value: String,
        events: Array,
        // 同分组下，共享下拉选择数据
        group: {
            type: String,
            default: 'default',
        },
        hasError: Boolean,
        errorText: String,
    },
    computed: {
        filteredCallbacks() {
            return this.callbacks.filter(d => d.indexOf(this.value || '') === 0)
        },
        callbacksChangeEventName() {
            return `callbacksChange-${this.group}`
        },
    },
    created() {
        this.events.forEach(e => {
            e.type == 'callback' && this.onCallbackChange(e.content)
        })
    },
    mounted() {
        document.addEventListener('click', this.onClickOtherArea)
        this.$bus.$on(this.callbacksChangeEventName, this.onCallbacksChange)
    },
    beforeDestroy() {
        document.removeEventListener('click', this.onClickOtherArea)
        this.$bus.$off(this.callbacksChangeEventName, this.onCallbacksChange)
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        onSelect(val) {
            if (val instanceof Event) {
                val = this.filteredCallbacks[0]
            }

            if (!val) {
                return
            }

            this.focus()
            this.$emit('input', val + '@')
        },
        onClickOtherArea(e) {
            if (!this.$refs.callbackInput.contains(e.target)) {
                this.show = false
            }
        },
        onCallbackChange(val) {
            if (!val) {
                return
            }

            const t = val.split('@')
            if (t.length == 2 && this.callbacks.indexOf(t[0]) === -1) {
                this.callbacks.push(t[0])
            }
        },
        onCallbacksChange(callbacks) {
            this.callbacks = callbacks
        },
    },
    watch: {
        focused(newValue) {
            if (newValue) {
                this.show = true
                this.$emit('focus', this.value)
            } else {
                this.$emit('blur', this.value)
                this.onCallbackChange(this.value)
            }
        },
        callbacks(newValue) {
            this.$bus.$emit(this.callbacksChangeEventName, newValue)
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.wm-input {
    width: 100% !important;
}

.wm-callback-input {
    position: relative;
}

.wm-autocomplete {
    width: 100%;
    position: absolute;
    border: $grey-border;
    background: #fff;
    top: 38px;
    z-index: 2;
    max-height: 200px;
    overflow: auto;

    .wm-item {
        padding: 10px;

        &:hover {
            background-color: #f1f1f1;
        }
    }
}
</style>
