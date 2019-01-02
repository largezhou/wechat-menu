<template>
    <table class="menu-events-table">
        <tr>
            <th
                v-for="(c, index) of columns"
                :key="index"
                :width="c.width"
            >{{ c.name }}
            </th>
            <th
                width="80"
                v-if="$scopedSlots.actions"
            >
                操作
            </th>
        </tr>
        <tr
            v-for="(e, index) of events"
            :key="index"
        >
            <td>
                <input
                    type="text"
                    class="input table-input"
                    v-model="e.remark"
                />
            </td>
            <td>
                <input
                    type="text"
                    class="input table-input"
                    v-model="e.key"
                />
            </td>
            <td
                class="cursor-pointer"
                title="点击切换类型"
                @click="onChangeType(index)"
            >
                <a href="javascript:void(0);">{{ typeText(e.type) }}</a>
            </td>
            <td>
                <textarea
                    v-if="e.type == 'msg'"
                    type="text"
                    class="input table-input"
                    v-model="e.content"
                    ref="inputs"
                    rows="2"
                />
                <callback-input
                    v-else
                    v-model="e.content"
                    :events="eventsForCallbacks"
                    ref="inputs"
                />
            </td>
            <td v-if="$scopedSlots.actions">
                <slot
                    name="actions"
                    :index="index"
                    :event="e"
                />
            </td>
        </tr>
    </table>
</template>

<script>
import { TYPES_TEXT } from '@/common/constants'
import CallbackInput from '@/components/CallbackInput'

export default {
    name: 'MenuEventsTable',
    components: {
        CallbackInput,
    },
    data() {
        return {
            columns: [
                {
                    field: 'remark',
                    name: '备注',
                    width: '150',
                },
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
            callbacks: [],
        }
    },
    props: {
        /**
         * 用来显示的 事件列表
         */
        events: Array,
        /**
         * 用来给 CallbackInput 组件 生成待选 回调命名空间用的事件列表
         */
        allEvents: Array,
    },
    computed: {
        eventsForCallbacks() {
            return this.allEvents
                ? this.allEvents
                : this.events
        },
    },
    methods: {
        typeText(type) {
            return TYPES_TEXT[type]
        },
        onChangeType(index) {
            const event = this.events[index]
            event.type = event.type == 'msg' ? 'callback' : 'msg'
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.menu-events-table {
    border: $grey-border;
    width: 100%;

    td,
    th {
        padding: 5px 10px;
        height: 45px;
        border: $grey-border;
        text-align: left;
    }
}

.event-type {
    cursor: pointer;
}

.table-input {
    width: 100% !important;
}
</style>
