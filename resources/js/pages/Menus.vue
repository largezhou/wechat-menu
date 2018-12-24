<template>
    <div class="edit-area">
        <div class="preview">
            <div class="header">
                <span class="text">公众号</span>
            </div>
            <div class="menus">
                <div class="menu">
                    <span>菜单名称</span>
                    <div class="sub-menus">
                        <div class="menu active">子菜单名称</div>
                        <div class="menu add">+</div>
                    </div>
                </div>
                <div class="menu">菜单名称</div>
                <div class="menu add">+</div>
            </div>
        </div>
        <div class="form">
        </div>
    </div>
</template>

<script>
import { getMenus } from '../api/wechat'

export default {
    name: 'Menus',
    data() {
        return {
            menus: [],
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            getMenus()
                .then(res => {
                    log(res.data)
                })
        },
    },
}
</script>

<style scoped lang="scss">
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

    .menus {
        height: 50px;
        background: #fafafa;
        border-top: 1px solid #e7e7eb;
        display: flex;
        line-height: 50px;
        position: relative;
        user-select: none;

        .menu {
            flex-grow: 1;
            text-align: center;
            cursor: move;
            color: #969696;
            overflow: hidden;
            border-left: 1px solid #e7e7eb;
            font-size: 15px;
            width: 33.33%;

            &:first-child {
                border-left: none;
            }

            &:hover {
                color: #000;
            }

            &.active {
                border: 1px solid #44b549;
                color: #44b549;
            }

            &.add {
                font-size: 35px;
                font-weight: 100;
                cursor: pointer;
            }
        }
    }

    .sub-menus {
        position: absolute;
        top: -115px;
        left: 33.33%;
        width: 100%;

        .menu {
            border: 1px solid #e7e7eb;

            &:first-child:not(.active) {
                border-bottom: none;
                border-left: 1px solid #e7e7eb;
            }
        }
    }
}
</style>
