<template>
    <div class="menu-manager">
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
                    >删除子菜单</a>
                </div>
                <div class="name-input-wrapper">
                    <span class="label">菜单名称</span>
                    <div class="input-wrapper">
                        <input
                            v-model="$global.currentMenu.name"
                            type="text"
                            class="input"
                        >
                        <span class="hint">仅支持中英文和数字，字数不超过8个汉字或16个字母</span>
                    </div>

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
import { getMenus, updateMenus } from '@/api/wechat'
import Menus from '@/components/Menus'
import { getBLen } from '@/common/utils'

/**
 * 需要验证的字段以及对应的验证方法
 * @type {{name(*=): *}}
 */
const FIELD_VALIDATES = {
    name(val) {
        const bLen = getBLen(val)

        return bLen > 0
            && bLen <= 16
    },
}

export default {
    name: 'MenuManager',
    components: {
        Menus,
    },
    data() {
        return {
            menus: [],
            menuAutoId: 1,
            saving: false,
        }
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
            const res = await getMenus()
            this.menus = res.data.menu.button

            this.menuAutoId = this.addUniqueKey(this.menus)

            this.$nextTick(() => {
                this.activeFirstMenu()
            })
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
                if (!this.validMenus(this.menus)) {
                    alert('有错误啊，不能发布')
                    return
                }

                this.saving = true
                const { data } = await updateMenus(this.menus)
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

        validMenus(menus) {
            return !menus.some(menu => {
                let anyError = false

                Object.keys(FIELD_VALIDATES)
                    .some(k => {
                        const cb = FIELD_VALIDATES[k]
                        const val = menu[k]

                        if (!cb(val)) {
                            anyError = true
                            return true
                        }
                    })

                if (anyError) {
                    this.$bus.$emit('menuActive', menu)
                    return true
                } else {
                    return !this.validMenus(menu.sub_button)
                }
            })
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

.menu-manager {
    max-width: $form-width + 20px + $preview-width;
    min-width: $form-min-width + 20px + $preview-width;
}

.form {
    padding: 0 20px;
    border: $grey-border;
    min-width: $form-min-width;
    width: $form-width;

    .header {
        height: 40px;
        line-height: 40px;
        border-bottom: $grey-border;
    }

    .label {
        display: inline-block;
        height: 40px;
        line-height: 40px;
        margin-right: 10px;
        vertical-align: top;
    }
}

.choose-hint {
    min-width: $form-min-width;
    width: $form-width;
    text-align: center;
    line-height: 600px;
    color: $hint-color;
}

.name-input-wrapper {
    margin-top: 40px;
}

.input-wrapper {
    display: inline-block;
}

.hint {
    display: block;
    color: $hint-color;
    font-size: 14px;
    padding: 5px 0;
}
</style>
