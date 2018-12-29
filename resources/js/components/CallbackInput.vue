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
            v-show="show && filteredData.length"
            class="autocomplete"
        >
            <template v-show="filteredData.length">
                <div
                    class="item cursor-pointer"
                    v-for="(i, index) of filteredData"
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
        }
    },
    props: {
        value: String,
        data: Array,
    },
    computed: {
        filteredData() {
            return this.data.filter(d => d.indexOf(this.value) === 0)
        },
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
                val = this.filteredData[0]
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
    },
    watch: {
        focused(newValue) {
            if (newValue) {
                this.show = true
                this.$emit('focus', this.value)
            } else {
                this.$emit('blur', this.value)
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
