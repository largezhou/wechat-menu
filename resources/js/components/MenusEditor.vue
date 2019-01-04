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
                    <div class="content">
                        <input
                            v-model="$global.currentMenu.name"
                            type="text"
                            class="input"
                            :class="{ 'has-error': hasError('name') }"
                        >
                        <span class="error-text">{{ getError('name') }}</span>
                    </div>
                </div>

                <div v-show="!currentHasSub" class="form-item">
                    <div class="form-item">
                        <span class="label">菜单内容</span>
                        <div class="content">
                            <div
                                class="radio-group"
                                :class="{ 'has-error': hasError('type') }"
                            >
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
                            <span class="error-text">{{ getError('type') }}</span>
                        </div>
                    </div>

                    <div class="form-item content-wrapper">
                        <component
                            :has-error="hasError(this.currentContentField)"
                            :error-text="getError(this.currentContentField)"
                            v-if="currentContentComponent"
                            :is="currentContentComponent"
                            :events="events"
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
            <button
                class="btn"
                @click="onReset"
            >重置</button>
        </div>
    </div>
</template>

<script>
import { getMenus, createMenus, getSettings } from '@/api/wechat'
import Menus from '@/components/Menus'
import ContentView from '@/components/ContentView'
import ContentEvent from '@/components/ContentEvent'
import { MENU_TYPES, WECHAT_ERROR_CODES } from '@/common/constants'
import { required, url } from 'vuelidate/lib/validators'

/**
 * 手动为无限嵌套的每个菜单单独生成验证
 * @param menus
 */
const buildMenusValidations = menus => {
    const validations = {}
    const validators = {
        name: {
            required,
        },
        type: {
            required,
        },
    }

    menus.forEach((menu, index) => {
        if (menu.sub_button.length > 0) {
            validations[index] = {
                name: validators.name,
                sub_button: buildMenusValidations(menu.sub_button),
            }
        } else {
            const t = { ...validators }

            if (menu.type == 'view') {
                t.url = {
                    required,
                    url,
                }
            } else {
                t.key = {
                    required,
                }
            }

            validations[index] = t
        }
    })

    return validations
}

export default {
    name: 'MenusEditor',
    components: {
        Menus,
        ContentView,
        ContentEvent,
    },
    validations() {
        return {
            menus: buildMenusValidations(this.menus),
        }
    },
    data() {
        return {
            menus: [],
            events: [],
            menuAutoId: 1,
            saving: false,

            fieldErrors: {
                name: {
                    required: '必须填写',
                },
                key: {
                    required: '必须选择',
                },
                type: {
                    required: '必须选择',
                },
                url: {
                    required: '必须填写',
                    url: '必须是有效的 URL',
                },
            },
        }
    },
    computed: {
        menuTypes() {
            return MENU_TYPES
        },
        currentHasSub() {
            return this.$global.currentMenu.sub_button.length > 0
        },
        currentContentType() {
            let type = this.$global.currentMenu.type
            return type == 'view' ? 'view' : 'event'
        },
        currentContentComponent() {
            let type = this.currentContentType

            return type ? `content-${type}` : null
        },
        currentContentField() {
            return this.currentContentType == 'view' ? 'url' : 'key'
        },
        /**
         * 当前激活菜单对应的验证相关数据
         */
        currentV() {
            const indexes = this.$global.currentMenuIndex.split('-')
            let cur = null
            let subs = this.$v.menus
            do {
                let i = indexes.shift()
                cur = subs[i]
                subs = cur.sub_button
            } while (indexes.length)

            return cur
        },
    },
    created() {
        this.getData()
    },
    methods: {
        async getData() {
            let res = await getMenus()
            this.menus = res.data.data.menu.button
            this.menusBak = JSON.stringify(this.menus)

            this.menuAutoId = this.addUniqueKey(this.menus)

            this.activeFirstMenu()

            res = await getSettings('menu_events')
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
            this.$v.$touch()

            if (this.$v.$invalid) {
                const errorMenu = this.getFirstErrorMenu()
                this.$bus.$emit('menuActive', errorMenu)
                return
            }

            try {
                this.saving = true
                const { data } = await createMenus(this.menus)

                if (data.status) {
                    this.$notice({
                        msg: data.msg,
                        type: 'success',
                    })
                } else {
                    this.$notice({
                        type: 'error',
                        duration: 6000,
                        msg(h) {
                            return h(
                                'div',
                                [
                                    h('span', data.msg),
                                    h('a', {
                                        attrs: {
                                            href: WECHAT_ERROR_CODES,
                                            target: '_blank',
                                        },
                                        style: {
                                            marginLeft: '10px',
                                        },
                                    }, '查看详情'),
                                ],
                            )
                        },
                    })
                }
            } finally {
                this.saving = false
            }
        },
        onRemoveCurrent() {
            if (!confirm('确认删除？')) {
                return
            }

            const [parent, sub] = this.$global.currentMenuIndex.split('-')

            let nextActive = null

            if (sub === undefined) {
                this.menus.splice(parent, 1)
            } else {
                const parentMenu = this.menus[parent]

                parentMenu.sub_button.splice(sub, 1)
                nextActive = parentMenu
            }

            this.$bus.$emit('menuActive', nextActive)
        },
        activeFirstMenu() {
            // 先清除当前的激活菜单，避免某些依赖于当前菜单数据的计算属性报错
            this.$bus.$emit('menuActive', null)
            this.$nextTick(() => {
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
            })
        },
        onReset() {
            this.menus = JSON.parse(this.menusBak)
            this.activeFirstMenu()
        },
        hasError(field) {
            const curV = this.currentV[field]
            return curV && curV.$invalid
        },
        getError(field) {
            const curV = this.currentV[field]
            if (!curV) {
                return
            }

            for (let i of Object.keys(curV.$params)) {
                if (!curV[i]) {
                    return this.fieldErrors[field][i]
                }
            }
        },
        getFirstErrorMenu(menus = this.menus, vMenus = this.$v.menus) {
            for (let i = 0; i < menus.length; i++) {
                const menu = menus[i]
                const vMenu = vMenus[i]

                // 如果菜单有错误，则返回他子菜单中的某个错误菜单，或者自己
                if (vMenu.$invalid) {
                    return this.getFirstErrorMenu(menu.sub_button, vMenu.sub_button) || menu
                }
            }

            return null
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

$preview-width: 300px;
$form-width: 1000px;
$form-min-width: 850px;

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
