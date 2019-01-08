<template>
    <button
        class="btn"
        :class="[`btn-${size}`]"
        @click="onRefresh"
    >刷新</button>
</template>

<script>
export default {
    name: 'Refresh',
    props: {
        onRefresh: {
            type: Function,
            required: true,
        },
        size: String,
    },
}
</script>

<style scoped>

</style>
