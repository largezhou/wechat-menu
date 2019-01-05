<template>
    <div>
        <span class="label hint grey-1">点击菜单会跳转到该链接</span>
        <w-input
            label="页面地址"
            v-bind="$props"
            v-model="$global.currentMenu.url"
            inline
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
.hint {
    display: block !important;
}
</style>
