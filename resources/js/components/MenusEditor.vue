<template>
    <div class="menus-editor">
        <div class="edit-area">
            <div class="preview">
                <div class="header">
                    <span class="text">公众号</span>
                </div>
                <menus :menus.sync="menus" :menu-auto-id.sync="menuAutoId"/>
            </div>
            <div
                v-if="$global.currentMenu"
                class="form"
            >
                <div class="header">
                    <span>{{ $global.currentMenu.name }}</span>
                    <a
                        href="javascript:void(0);"
                        class="pull-right"
                        @click="onRemoveCurrent"
                    >删除菜单</a>
                </div>
                <div class="form-item name-item">
                    <span class="label">菜单名称</span>
                    <input
                        v-model="$global.currentMenu.name"
                        type="text"
                        class="input"
                    >
                </div>

                <div v-show="!currentHasSub" class="form-item">
                    <div class="form-item">
                        <span class="label">菜单内容</span>
                        <label
                            class="cursor-pointer"
                            v-for="key of Object.keys(menuTypes)"
                            :key="key"
                        >
                            <input
                                type="radio"
                                name="type"
                                :value="key"
                                v-model="$global.currentMenu.type"
                            >
                            {{ menuTypes[key] }}
                        </label>
                    </div>

                    <div class="form-item content-wrapper">
                        <component
                            v-if="currentContentComponent"
                            :is="currentContentComponent"
                            :events="mappedEvents"
                        />
                    </div>
                </div>
                <div v-show="currentHasSub" style="margin-top: 20px;">
                    <span class="grey-1">已设置子菜单，只能编辑菜单名</span>
                </div>
            </div>
            <div
                v-else
                class="choose-hint"
            >在左侧选择菜单编辑
            </div>
        </div>
        <div class="footer-toolbar">
            <button
                class="btn btn-primary"
                @click="onSave"
                :disabled="saving"
            >保存</button>
        </div>
    </div>
</template>

<script>
import { getMenus, createMenus, getEvents } from '@/api/wechat'
import Menus from '@/components/Menus'
import ContentView from '@/components/ContentView'
import ContentEvent from '@/components/ContentEvent'

const MENU_TYPES = {
    click: '点击',
    view: '链接',
    scancode_push: '扫码推',
    scancode_waitmsg: '扫码推提示框',
    pic_sysphoto: '拍照发图',
    pic_photo_or_album: '拍照或相册发图',
    pic_weixin: '相册发图',
    location_select: '地理位置',
}

export default {
    name: 'MenuManager',
    components: {
        Menus,
        ContentView,
        ContentEvent,
    },
    data() {
        return {
            menus: [],
            events: [],
            menuAutoId: 1,
            saving: false,
        }
    },
    computed: {
        menuTypes() {
            return MENU_TYPES
        },
        currentHasSub() {
            return this.$global.currentMenu.sub_button.length > 0
        },
        currentContentComponent() {
            let type = this.$global.currentMenu.type
            type = type == 'view' ? 'view' : 'event'

            return type ? `content-${type}` : null
        },
        mappedEvents() {
            const mapped = {}

            this.events.forEach(e => {
                mapped[e.key] = e
            })

            return mapped
        },
    },
    created() {
        this.getData()
    },
    mounted() {
        this.$bus.$on('removeMenu', this.onRemoveMenu)
    },
    beforeDestroy() {
        this.$bus.$off('removeMenu', this.onRemoveMenu)
    },
    methods: {
        async getData() {
            let res = await getMenus()
            this.menus = res.data.data.menu.button

            this.menuAutoId = this.addUniqueKey(this.menus)

            this.$nextTick(() => {
                this.activeFirstMenu()
            })

            res = await getEvents()
            this.events = res.data.data
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
            try {
                this.saving = true
                const { data } = await createMenus(this.menus)
                alert(data.errmsg)
            } finally {
                this.saving = false
            }
        },
        onRemoveCurrent() {
            if (confirm('确认删除？')) {
                this.$bus.$emit('removeCurrent')
            }
        },
        onRemoveMenu({ parent, sub }) {
            let nextActive = null

            if (sub === undefined) {
                this.menus.splice(parent, 1)
            } else {
                const parentMenu = this.menus[parent]

                parentMenu.sub_button.splice(sub, 1)
                nextActive = parentMenu
            }

            this.$nextTick(() => {
                this.$bus.$emit('menuActive', nextActive)
            })
        },
        activeFirstMenu() {
            if (this.menus.length == 0) {
                return
            }

            let menu = null
            const subs = this.menus[0].sub_button
            if (subs.length == 0) {
                menu = this.menus[0]
            } else {
                menu = subs[0]
            }

            this.$bus.$emit('menuActive', menu)
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

$preview-width: 300px;
$form-width: 1000px;
$form-min-width: 800px;

.edit-area {
    height: 600px;
    display: flex;
}

.preview {
    min-width: $preview-width;
    margin-right: 20px;
    border: $grey-border;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

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

.menus-editor {
    max-width: $form-width + 20px + $preview-width;
    min-width: $form-min-width + 20px + $preview-width;
}

.form {
    padding: 0 20px;
    border: $grey-border;
    min-width: $form-min-width;
    width: $form-width;
    background-color: #f4f5f9;

    .header {
        height: 40px;
        line-height: 40px;
        border-bottom: $grey-border;
        border-width: 2px;
    }

    .content-wrapper {
        border: $grey-border;
        background-color: #fff;
        padding: 20px;
    }
}

.choose-hint {
    min-width: $form-min-width;
    width: $form-width;
    text-align: center;
    line-height: 600px;
    color: $hint-color;
}

.name-item {
    margin-top: 30px;
}
</style>
