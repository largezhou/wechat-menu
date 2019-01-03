<template>
    <table>
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
            <td>
                <change-handle-type :event="e"/>
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
                    group="menuEvents"
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
import CallbackInput from '@/components/CallbackInput'
import ChangeHandleType from '@/components/ChangeHandleType'

export default {
    name: 'MenuEventsTable',
    components: {
        CallbackInput,
        ChangeHandleType,
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
}
</script>

<style scoped lang="scss">
</style>
