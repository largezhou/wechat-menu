<template>
    <div class="menus">
        <menu-item
            v-for="(menu, index) of menus"
            :menu="menu"
            v-dragging="{ item: menu, list: menus, group: 'column' }"
            :index="index"
            :key="menu.id"
            :menu-width="menuWidth"
            is-parent
        />

        <menu-item
            v-if="!columnIsMaximum"
            add
            :menu-width="menuWidth"
            @click.native="onAddMenu"
        />
    </div>
</template>

<script>
import MenuItem from '@/components/MenuItem'
import { MAX_COLUMN, MAX_SUB_COUNT } from '@/constants'

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
        menuAutoId: Number,
    },
    mounted() {
        this.$root.$on('addSubMenu', this.onAddMenu)
    },
    beforeDestroy() {
        this.$root.$off('addSubMenu', this.onAddMenu)
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
    methods: {
        onAddMenu(parentIndex) {
            let id

            if (parentIndex instanceof Event) {
                id = this.addColumn()
            } else {
                id = this.addSubMenu(parentIndex)
            }

            this.$nextTick(() => {
                this.$root.$emit('menuActive', id)
            })
        },
        addColumn() {
            if (this.columnIsMaximum) {
                return
            }

            return this.realAddMenu(this.menus)
        },
        addSubMenu(parentIndex) {
            const menu = this.menus[parentIndex]
            const subMenus = menu ? menu.sub_button : null

            if (!subMenus || subMenus.length == MAX_SUB_COUNT) {
                return
            }

            return this.realAddMenu(subMenus, true)
        },
        realAddMenu(menus, isSub = false) {
            const id = this.menuAutoId

            menus.push({
                name: (isSub ? '子' : '') + '菜单名称',
                type: 'click',
                id,
                sub_button: [],
            })

            this.$emit('update:menuAutoId', id + 1)

            return id
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
