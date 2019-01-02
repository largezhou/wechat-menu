<template>
    <div class="events-setting">
        <events-table
            :events="events"
            ref="eventsTable"
        >
            <template slot-scope="{ index, event }">
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
import { createEvents, getEvents } from '@/api/wechat'
import EventsTable from '@/components/EventsTable'

export default {
    name: 'EventsSetting',
    components: {
        EventsTable,
    },
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
                key: Math.random().toString(32).substr(2),
                type: 'callback',
                content: '',
            })

            this.$nextTick(() => {
                this.$refs.eventsTable.$refs.inputs[this.events.length - 1].focus()
            })
        },
        async onSave() {
            const valid = this.valid()
            if (valid !== true) {
                this.$notice({
                    msg: valid,
                    type: 'error',
                })
                return
            }

            try {
                this.saving = true
                await createEvents(this.events)
            } finally {
                this.saving = false
            }
        },
        valid() {
            const keys = []
            const remarks = []
            let errorMsg

            this
                .events
                .every((e, index) => {
                    const prefix = `第 ${index + 1} 个配置的`

                    if (!e.remark) {
                        errorMsg = prefix + '备注不能为空'
                        return false
                    }

                    if (remarks.indexOf(e.remark) !== -1) {
                        errorMsg = prefix + '备注不能重复'
                        return false
                    }

                    if (!e.key) {
                        errorMsg = prefix + '事件标识不能为空'
                        return false
                    }

                    if (keys.indexOf(e.key) !== -1) {
                        errorMsg = prefix + '事件标识不能重复'
                        return false
                    }

                    if (!e.content) {
                        errorMsg = prefix + '内容不能为空'
                        return false
                    }

                    if (e.type == 'callback' && e.content.split('@').length != 2) {
                        errorMsg = prefix + '内容格式不对'
                        return false
                    }

                    keys.push(e.key)
                    remarks.push(e.remark)

                    return true
                })

            return errorMsg || true
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
