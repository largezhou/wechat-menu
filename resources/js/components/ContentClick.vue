<template>
    <div>
        <div class="form-item click-item">
            <span class="label">事件 KEY</span>
            <input
                v-model="$global.currentMenu.key"
                type="text"
                class="input"
                placeholder="点击事件 KEY"
            >
        </div>
        <div class="form-item click-item">
            <span class="label">处理方法</span>
            <input
                v-model="$global.currentMenu.callback"
                type="text"
                class="input key-callback-input"
                placeholder="Path\To\HandlerClass@method"
            >
        </div>
    </div>
</template>

<script>
export default {
    name: 'ContentClick',
}
</script>

<style scoped lang="scss">
.click-item {
    .label {
        width: 80px;
    }
}

.key-callback-input {
    width: 600px !important;
}
</style>
