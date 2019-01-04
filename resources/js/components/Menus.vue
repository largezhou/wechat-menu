<template>
    <div class="menus">
        <draggable
            v-model="menusData"
            :options="{ group: 'column' }"
            style="display: flex"
            :style="{ width: `${menuWidth * menus.length}%` }"
        >
            <menu-item
                v-for="(menu, index) of menus"
                :menu="menu"
                :index="index"
                :deep-index="index.toString()"
                :key="menu.id"
                :menu-width="menuWidth"
                is-parent
            />
        </draggable>

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
import { MAX_COLUMN, MAX_SUB_COUNT } from '@/common/constants'
import Draggable from 'vuedraggable'

export default {
    name: 'Menus',
    components: {
        MenuItem,
        Draggable,
    },
    data() {
        return {
            menusData: this.menus,
        }
    },
    props: {
        menus: {
            type: Array,
            default: () => ([]),
        },
        menuAutoId: Number,
    },
    mounted() {
        this.$bus.$on('addSubMenu', this.onAddMenu)
    },
    beforeDestroy() {
        this.$bus.$off('addSubMenu', this.onAddMenu)
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
            return 1 / this.columnsCount * 100
        },
    },
    methods: {
        onAddMenu(parentIndex) {
            let menu

            if (parentIndex instanceof Event) {
                menu = this.addColumn()
            } else {
                menu = this.addSubMenu(parentIndex)
            }

            this.$nextTick(() => {
                this.$bus.$emit('menuActive', menu)
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

            const menu = {
                name: (isSub ? '子' : '') + '菜单名称',
                type: 'click',
                key: '',
                callback: '',
                id,
                sub_button: [],
            }
            menus.push(menu)

            this.$emit('update:menuAutoId', id + 1)

            return menu
        },
    },
    watch: {
        menus(newValue) {
            this.menusData = newValue
        },
        menusData(newValue) {
            this.$emit('update:menus', newValue)
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
