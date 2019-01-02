<template>
    <div class="events-setting">
        <events-table
            :events="events"
            ref="eventsTable"
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
        </events-table>
        <div class="empty-table">
            <button
                class="btn btn-primary"
                @click="onSave"
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
import { getEvents } from '@/api/wechat'
import EventsTable from '@/components/EventsTable'
import { uniqueKey } from '@/common/utils'
import CreateEvents from '@/common/create-events'

export default {
    name: 'EventsSetting',
    components: {
        EventsTable,
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
            const { data } = await getEvents()
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
                this.$refs.eventsTable.$refs.inputs[this.events.length - 1].focus()
            })
        },
        onReset() {
            this.events = JSON.parse(this.eventsBak)
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.events-setting {
    width: 1000px;
}

.empty-table {
    text-align: center;
    color: $grey-1;
    padding: 20px 0;
    border: $grey-border;
    border-top: none;
}
</style>
