<template>
    <div class="wm-wechat-menu">
        <div class="wm-tabs">
            <a
                class="wm-tab"
                v-for="k of Object.keys(pageMap)"
                :key="k"
                :href="`#${k}`"
                :class="{ 'wm-active': hash == k }"
            >{{ pageMap[k] }}</a>
        </div>
        <div class="wm-tab-content">
            <keep-alive>
                <component :is="hash"/>
            </keep-alive>
        </div>
    </div>
</template>

<script>
import { PAGES } from '@/common/constants'

export default {
    name: 'WechatMenu',
    data() {
        return {
            hash: '',
            pageMap: PAGES,
        }
    },
    mounted() {
        this.onHashChange()
        window.addEventListener('hashchange', this.onHashChange)
    },
    beforeDestroy() {
        window.removeEventListener('hashchange', this.onHashChange)
    },
    methods: {
        onHashChange() {
            const hash = location.hash.slice(1)
            if (PAGES[hash]) {
                this.hash = hash
            } else {
                this.hash = Object.keys(PAGES)[0]
            }
        },
    },
}
</script>

<style scoped lang="scss">
@import '~@/../sass/vars';

.wm-wechat-menu {
    width: $page-width;
}
</style>
