<template>
    <div class="menus">
        <menu-item
            v-for="(menu, index) of menus"
            :menu="menu"
            :key="index"
            :menu-width="menuWidth"
        />

        <menu-item
            v-if="!columnIsMaximum"
            add
            :menu-width="menuWidth"
        />
    </div>
</template>

<script>
import MenuItem from '@/components/MenuItem'

/**
 * 一级菜单最大数量
 * @type {number}
 */
const MAX_COLUMN = 3

export default {
    name: 'Menus',
    components: {
        MenuItem,
    },
    props: {
        menus: {
            type: Array,
            default: () => ([]),
        },
    },
    computed: {
        columnIsMaximum() {
            return this.menus.length == MAX_COLUMN
        },
        columnsCount() {
            const count = this.menus.length
            if (this.columnIsMaximum || count == MAX_COLUMN - 1) {
                return MAX_COLUMN
            } else {
                return count + 1
            }
        },
        menuWidth() {
            return `${1 / this.columnsCount * 100}%`
        },
    },
}
</script>

<style scoped lang="scss">
.menus {
    height: 50px;
    background: #fafafa;
    border-top: 1px solid #e7e7eb;
    display: flex;
    user-select: none;
}
</style>
