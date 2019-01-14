<template>
    <div>
        <span
            style="padding: 10px;margin-left: 10px;cursor: pointer"
            @click="value > 1 && $emit('input', value - 1)"
        >上一页</span>
        <span
            v-for="i of totalPage"
            :key="i"
            style="padding: 10px;margin-left: 10px;cursor: pointer"
            :style="{ color: value == i ? 'red' : 'black' }"
            @click="$emit('input', i)"
        >{{ i }}</span>
        <span
            style="padding: 10px;margin-left: 10px;cursor: pointer"
            @click="$emit('input', value + 1)"
        >下一页</span>
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
    },
}
</script>

<style scoped lang="scss">

</style>
