<template>
    <div class="edit-area">
        <div class="preview">
            <div class="header">
                <span class="text">公众号</span>
            </div>
            <div class="actions">
                <div class="action">菜单名称</div>
                <div class="action active">菜单名称</div>
                <div class="action add">+</div>
            </div>
        </div>
        <div class="form">
        </div>
    </div>
</template>

<script>
export default {
    name: 'Menus',
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

    .actions {
        height: 50px;
        background: #fafafa;
        border-top: 1px solid #e7e7eb;
        display: flex;
        line-height: 50px;

        .action {
            width: 100%;
            flex-grow: 1;
            text-align: center;
            cursor: pointer;
            color: #969696;
            overflow: hidden;
            border-left: 1px solid #e7e7eb;

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
            }
        }
    }
}
</style>
