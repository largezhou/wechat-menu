<template>
    <button
        class="wm-btn"
        :class="[`wm-btn-${size}`]"
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
