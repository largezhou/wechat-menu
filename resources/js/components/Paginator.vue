<template>
    <div
        class="wm-paginator"
        v-show="totalPage"
    >
        <span
            class="wm-page"
            @click="goPage(value - 1)"
        >&lt;</span>

        <span
            v-show="veryLeftPage > 1"
            class="wm-page"
            @click="goPage(1)"
        >1</span>

        <span
            v-show="veryLeftPage > 2"
            class="wm-page wm-dot"
        >···</span>

        <span
            class="wm-page"
            v-for="i of pagesInRange"
            :key="i"
            :class="{ 'wm-active': value == i }"
            @click="goPage(i)"
        >{{ i }}</span>

        <span
            v-show="veryRightPage < totalPage - 1"
            class="wm-page wm-dot"
        >···</span>

        <span
            v-show="veryRightPage < totalPage"
            class="wm-page"
            @click="goPage(totalPage)"
        >{{ totalPage }}</span>

        <span
            class="wm-page"
            @click="goPage(value + 1)"
        >&gt;</span>
    </div>
</template>

<script>
export default {
    name: 'Paginator',
    data() {
        return {
            // 最大页数按钮数量，多余的会显示为 `...`
            btnsCount: 7,
        }
    },
    props: {
        value: [String, Number],
        total: [String, Number],
        per: [String, Number],
    },
    computed: {
        totalPage() {
            if (!this.per) {
                return 0
            }

            return Math.ceil(this.total / this.per)
        },
        veryLeftPage() {
            let val
            if ((this.value + Math.floor(this.btnsCount / 2)) >= this.totalPage) {
                val = this.totalPage - this.btnsCount
            } else {
                val = this.value - Math.floor(this.btnsCount / 2)
            }

            return val < 1 ? 1 : val
        },
        veryRightPage() {
            let val
            if ((this.value - Math.floor(this.btnsCount / 2)) <= 1) {
                val = this.btnsCount + 1
            } else {
                val = this.value + Math.floor(this.btnsCount / 2)
            }

            return val > this.totalPage ? this.totalPage : val
        },
        pagesInRange() {
            const p = []
            for (let i = this.veryLeftPage; i <= this.veryRightPage; i++) {
                p.push(i)
            }

            return p
        },
    },
    methods: {
        goPage(page) {
            if (page >= 1) {
                this.$emit('input', page)
            }
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars";

.wm-paginator {
    display: flex;
}

.wm-page {
    cursor: pointer;
    user-select: none;
    padding: 5px 10px;
    border: $grey-border;
    min-width: 40px;
    text-align: center;
    margin-left: -1px;

    &:first-child {
        margin-left: 0;
    }

    &.wm-active {
        background-color: $main-color;
        color: white;
        border-color: $main-color;
    }

    &:hover:not(.wm-active) {
        color: $main-color;
    }

    &.wm-dot {
        cursor: initial;
        &:hover {
            color: initial;
        }
    }
}
</style>
