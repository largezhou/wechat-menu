<template>
    <div>
        <span class="label hint grey-1">点击菜单会打开该小程序</span>
        <w-input
            label="APP ID"
            :has-error="hasError('appid')"
            :error-text="getError('appid')"
            v-model="$global.currentMenu.appid"
            inline
        />
        <w-input
            label="打开页面"
            :has-error="hasError('pagepath')"
            :error-text="getError('pagepath')"
            v-model="$global.currentMenu.pagepath"
            inline
        />
        <w-input
            label="备用链接"
            :has-error="hasError('url')"
            :error-text="getError('url')"
            v-model="$global.currentMenu.url"
            inline
        />
    </div>
</template>

<script>
import singleErrorHelper from '@/common/single-error-helper'

export default {
    name: 'ContentMini',
    mixins: [
        singleErrorHelper,
    ],
    props: {
        currentV: Object,
        fieldErrors: Object,
    },
}
</script>

<style lang="scss" scoped>
.hint {
    display: block !important;
    margin-bottom: 15px;
}
</style>
