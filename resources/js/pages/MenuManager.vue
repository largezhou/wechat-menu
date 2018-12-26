<template>
    <div>
        <div class="edit-area">
            <div class="preview">
                <div class="header">
                    <span class="text">公众号</span>
                </div>
                <menus :menus.sync="menus" :menu-auto-id.sync="menuAutoId"/>
            </div>
            <div class="form">
            </div>
        </div>
        <div class="footer-toolbar">
            <button
                class="btn btn-primary"
                @click="onSave"
            >保存</button>
        </div>
    </div>
</template>

<script>
import { getMenus, updateMenus } from '@/api/wechat'
import Menus from '@/components/Menus'

export default {
    name: 'MenuManager',
    components: {
        Menus,
    },
    data() {
        return {
            menus: [],
            menuAutoId: 1,
        }
    },
    created() {
        this.getData()
    },
    methods: {
        async getData() {
            const res = await getMenus()
            this.menus = res.data.menu.button

            this.menuAutoId = this.addUniqueKey(this.menus)
        },

        /**
         * 给菜单加上唯一标识
         *
         * @param menus
         * @param id
         */
        addUniqueKey(menus, id = 1) {
            menus.forEach(item => {
                item.id = id++
                id = this.addUniqueKey(item.sub_button, id)
            })

            return id
        },
        async onSave() {
            const { data } = await updateMenus(this.menus)

            alert(data.errmsg)
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.edit-area {
    height: 600px;
    display: flex;

    .preview {
        min-width: 300px;
        margin-right: 20px;
        border: 1px solid #e7e7eb;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .form {
        min-width: 800px;
        width: 1000px;
        background-color: green;
    }

    .header {
        height: 50px;
        background: #3a3a3e;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}

.footer-toolbar {
    margin-top: 30px;
    text-align: center;
}
</style>
