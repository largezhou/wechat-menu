<template>
    <div class="callback-input" ref="callbackInput">
        <input
            type="text"
            class="input"
            :value="value"
            ref="input"
            @focus="focused = true"
            @blur="focused = false"
            @input="$emit('input', $event.target.value)"
            @keydown.enter="onSelect"
        />
        <div
            v-show="show && filteredCallbacks.length"
            class="autocomplete"
        >
            <template v-show="filteredCallbacks.length">
                <div
                    class="item cursor-pointer"
                    v-for="(i, index) of filteredCallbacks"
                    :key="index"
                    @click="onSelect(i)"
                >
                    {{ i }}
                </div>
            </template>
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
    },
    computed: {
        filteredCallbacks() {
            return this.callbacks.filter(d => d.indexOf(this.value) === 0)
        },
    },
    created() {
        this.events.forEach(e => {
            e.type == 'callback' && this.onCallbackChange(e.content)
        })
    },
    mounted() {
        document.addEventListener('click', this.onClickOtherArea)
    },
    beforeDestroy() {
        document.removeEventListener('click', this.onClickOtherArea)
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
            const t = val.split('@')
            if (t.length == 2 && this.callbacks.indexOf(t[0]) === -1) {
                this.callbacks.push(t[0])
            }
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
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.input {
    width: 100% !important;
}

.callback-input {
    position: relative;
}

.autocomplete {
    width: 100%;
    position: absolute;
    border: $grey-border;
    background: #fff;
    top: 38px;
    z-index: 2;
    max-height: 200px;
    overflow: auto;

    .item {
        padding: 10px;

        &:hover {
            background-color: #f1f1f1;
        }
    }
}
</style>
