<template>
    <div class="events-setting">
        <table class="events-table">
            <tr>
                <th
                    v-for="(c, index) of columns"
                    :key="index"
                    :width="c.width"
                >{{ c.name }}
                </th>
                <th width="80">操作</th>
            </tr>
            <template v-if="events.length > 0">
                <tr
                    v-for="(e, index) of events"
                    :key="index"
                >
                    <td
                        v-for="({ field }, index) of columns"
                        :key="index"
                    >{{ field == 'type' ? typesText[e[field]] : e[field] }}
                    </td>
                    <td>
                        <button
                            class="btn btn-danger btn-sm"
                            @click="onRemove(index)"
                        >删除</button>
                    </td>
                </tr>
            </template>
        </table>
        <div class="empty-table">
            <button
                class="btn btn-primary"
                @click="onSave"
            >保存</button>
            <button
                class="btn"
                @click="onNewEvent"
            >添加一个</button>
        </div>
    </div>
</template>

<script>
import { getEvents } from '@/api/wechat'

export default {
    name: 'EventsSetting',
    data() {
        return {
            events: [],
            columns: [
                {
                    field: 'key',
                    name: '事件标识',
                    width: '150',
                },
                {
                    field: 'type',
                    name: '处理方法',
                    width: '100',
                },
                {
                    field: 'content',
                    name: '内容',
                },
            ],
            typesText: {
                msg: '自动回复',
                callback: '事件处理',
            },
        }
    },
    created() {
        this.getData()
    },
    methods: {
        async getData() {
            const { data } = await getEvents()
            this.events = data
        },
        onRemove(index) {
            this.events.splice(index, 1)
        },
        onNewEvent() {
            this.events.push({
                key: 'some random key',
                type: 'msg',
                content: 'Hello World',
            })
        },
        onSave() {

        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.events-setting {
    width: 800px;
}

.events-table {
    border: $grey-border;
    width: 100%;

    td,
    th {
        padding: 0 10px;
        height: 45px;
        border: $grey-border;
        text-align: left;
    }
}

.empty-table {
    text-align: center;
    color: $grey-1;
    padding: 20px 0;
    border: $grey-border;
    border-top: none;
}
</style>
