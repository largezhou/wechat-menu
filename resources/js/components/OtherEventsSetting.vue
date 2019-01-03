<template>
    <div class="other-events-setting">
        <table>
            <tr>
                <th
                    v-for="(c, index) of columns"
                    :key="index"
                    :width="c.width"
                >{{ c.name }}
                </th>
            </tr>
            <tr
                v-for="(e, index) of events"
                :key="index"
            >
                <td :title="e.key">{{ typeText(e.key) }}</td>
                <td>
                    <change-handle-type :event="e"/>
                </td>
                <td>
                    <textarea
                        v-if="e.type == 'msg'"
                        class="input table-input"
                        v-model="e.content"
                        ref="inputs"
                        rows="2"
                    />
                    <callback-input
                        v-else
                        v-model="e.content"
                        :events="[]"
                        ref="inputs"
                    />
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import ChangeHandleType from '@/components/ChangeHandleType'
import CallbackInput from '@/components/CallbackInput'
import { OTHER_EVENT_TYPES } from '@/common/constants'
import { getSettings } from '@/api/wechat'

export default {
    name: 'OtherEventsSetting',
    components: {
        ChangeHandleType,
        CallbackInput,
    },
    data() {
        return {
            columns: [
                {
                    field: 'name',
                    name: '事件',
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
            events: [
                {
                    key: 'subscribe',
                    type: 'msg',
                    content: '欢迎订阅',
                },
                {
                    key: 'unsubscribe',
                    type: 'callback',
                    content: 'App\\Services\\WechatService@scanResult',
                },
                {
                    key: 'none',
                    type: 'callback',
                    content: 'App\\Services\\WechatService@scanResult',
                },
            ],
        }
    },
    created() {
        this.getData()
    },
    methods: {
        typeText(type) {
            return OTHER_EVENT_TYPES[type] || type
        },
        async getData() {
            const { data } = await getSettings('other_events')

            log(data)
        },
    },
}
</script>

<style scoped lang="scss">
.other-events-setting {
    width: 1000px;
}
</style>
