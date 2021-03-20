<template>
    <div class="wm-menu-events-setting">
        <menu-events-table
            :events="events"
            ref="menuEventsTable"
        >
            <template
                slot="actions"
                slot-scope="{ index, event }"
            >
                <button
                    class="wm-btn wm-btn-danger wm-btn-sm"
                    @click="onRemove(index)"
                >删除</button>
            </template>
        </menu-events-table>
        <div class="wm-footer-toolbar">
            <button
                class="wm-btn wm-btn-primary"
                @click="onSave($refs.menuEventsTable.$v, events, 'menu_events')"
                :disabled="saving"
            >保存</button>
            <refresh :on-refresh="getData"/>
            <button
                class="wm-btn"
                @click="onReset"
            >重置</button>
            <button
                class="wm-btn"
                @click="onNewEvent"
            >添加一个</button>
        </div>
    </div>
</template>

<script>
import { getResources } from '@/api/wechat'
import MenuEventsTable from '@/components/MenuEventsTable'
import { uniqueKey } from '@/common/utils'
import createEvents from '@/common/create-events'

export default {
    name: 'MenuEventsSetting',
    components: {
        MenuEventsTable,
    },
    mixins: [
        createEvents,
    ],
    data() {
        return {
            events: [],
            saving: false,
        }
    },
    created() {
        this.getData()
    },
    computed: {
        keys() {
            return this.events.map(e => e.key)
        },
    },
    methods: {
        async getData() {
            const { data } = await getResources('menu_events')
            this.events = data.data
            this.eventsBak = JSON.stringify(this.events)
        },
        onRemove(index) {
            confirm('确定删除？') && this.events.splice(index, 1)
        },
        onNewEvent() {
            this.events.push({
                key: uniqueKey(),
                type: 'callback',
                content: '',
            })

            this.$nextTick(() => {
                this.$refs.menuEventsTable.$refs.inputs[this.events.length - 1].focus()
            })
        },
        onReset() {
            this.events = JSON.parse(this.eventsBak)
        },
    },
}
</script>
