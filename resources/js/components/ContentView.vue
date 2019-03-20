<template>
    <div>
        <span class="wm-label wm-hint wm-grey-1">点击菜单会跳转到该链接</span>
        <w-input
            label="页面地址"
            v-bind="$props"
            v-model="$global.currentMenu.url"
            inline
            error-inside
        />
    </div>
</template>

<script>
export default {
    name: 'ContentView',
    props: {
        hasError: Boolean,
        errorText: String,
    },
}
</script>

<style lang="scss" scoped>
.wm-hint {
    display: block !important;
    margin-bottom: 15px;
}
</style>
