<template>
    <div>
        <span class="label grey-1">点击菜单会跳转到该链接</span>
        <div class="form-item">
            <span class="label">页面地址</span>
            <div class="content">
                <input
                    v-model="$global.currentMenu.url"
                    type="text"
                    class="input"
                    :class="{ 'has-error': hasError }"
                >
                <span class="error-text">{{ errorText }}</span>
            </div>
        </div>
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

<style lang="scss">

</style>
