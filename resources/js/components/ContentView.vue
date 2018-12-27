<template>
    <div>
        <span class="label grey-1">点击菜单会跳转到该链接</span>
        <div class="form-item">
            <span class="label">页面地址</span>
            <input
                v-model="$global.currentMenu.url"
                type="text"
                class="input"
            >
        </div>
    </div>
</template>

<script>
export default {
    name: 'ContentView',
}
</script>

<style lang="scss">

</style>
