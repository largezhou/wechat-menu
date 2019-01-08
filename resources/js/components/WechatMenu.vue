<template>
    <div class="wechat-menu">
        <div class="tabs">
            <a
                class="tab"
                v-for="k of Object.keys(pageMap)"
                :key="k"
                :href="`#${k}`"
                :class="{ active: hash == k }"
            >{{ pageMap[k] }}</a>
        </div>
        <div class="tab-content">
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

$tab-height: 50px;

.wechat-menu {
    width: $page-width;

    .tabs {
        height: $tab-height;
        line-height: $tab-height;
        border-bottom: 3px solid $main-color;
        box-sizing: content-box !important;
        margin-bottom: 20px;
    }

    .tab {
        color: $grey-1 !important;
        display: inline-block;
        padding: 0 20px;

        &:hover {
            background-color: $grey;
        }

        &.active {
            background-color: $main-color;
            color: #fff !important;
        }
    }
}
</style>
