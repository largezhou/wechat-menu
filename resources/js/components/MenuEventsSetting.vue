<template>
    <div class="menu-events-setting">
        <menu-events-table
            :events="events"
            ref="menuEventsTable"
        >
            <template
                slot="actions"
                slot-scope="{ index, event }"
            >
                <button
                    class="btn btn-danger btn-sm"
                    @click="onRemove(index)"
                >删除</button>
            </template>
        </menu-events-table>
        <div class="footer-toolbar">
            <button
                class="btn btn-primary"
                @click="onSave($refs.menuEventsTable.$v)"
                :disabled="saving"
            >保存</button>
            <button
                class="btn"
                @click="onReset"
            >重置</button>
            <button
                class="btn"
                @click="onNewEvent"
            >添加一个</button>
        </div>
    </div>
</template>

<script>
import { getSettings } from '@/api/wechat'
import MenuEventsTable from '@/components/MenuEventsTable'
import { uniqueKey } from '@/common/utils'
import CreateEvents from '@/common/create-events'

export default {
    name: 'MenuEventsSetting',
    components: {
        MenuEventsTable,
    },
    mixins: [
        CreateEvents,
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
            const { data } = await getSettings('menu_events')
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

<style scoped lang="scss">
.menu-events-setting {
    width: 1000px;
}
</style>
