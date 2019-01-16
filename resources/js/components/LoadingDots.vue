<template>
    <span :style="{ cursor: loading ? 'not-allowed': '' }">
        <span v-show="loading">
            <span
                v-for="(i, index) in num"
                :style="{ visibility: index >= count ? 'hidden' : '' }"
            >·</span>
        </span>
        <span v-show="!loading">{{ text }}</span>
    </span>
</template>

<script>
export default {
    name: 'LoadingDots',
    data() {
        return {
            count: 0,
        }
    },
    props: {
        loading: Boolean,
        num: Number,
        text: String,
    },
    beforeDestroy() {
        clearInterval(this.interval)
    },
    watch: {
        loading: {
            handler(newValue) {
                if (newValue) {
                    this.$nextTick(() => {
                        this.interval = setInterval(() => {
                            this.count++
                            if (this.count > this.num) {
                                this.count = 1
                            }
                        }, 100)
                    })
                } else {
                    clearInterval(this.interval)
                    this.count = 0
                }
            },
            immediate: true,
        },
    },
}
</script>
