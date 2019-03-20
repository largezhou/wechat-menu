<template>
    <div>
        <w-select
            class="wm-vertical-middle"
            label="选择事件"
            :has-error="hasError"
            :error-text="errorText"
            v-model="$global.currentMenu.key"
            inline
            error-inside
            :options="events"
            value-field="key"
            text-field="remark"
        />
        <div class="wm-vertical-middle">
            <button
                v-show="!newEvent"
                type="button"
                class="wm-btn wm-btn-primary wm-btn-sm"
                @click="onNewEvent"
            >添加</button>
            <div v-show="newEvent" style="display: inline-block">
                <button
                    type="button"
                    class="wm-btn wm-btn-primary wm-btn-sm"
                    @click="onSaveEvent"
                    :disabled="saving"
                >确定</button>
                <button
                    type="button"
                    class="wm-btn wm-btn-sm"
                    @click="newEvent = null"
                >取消</button>
            </div>
        </div>

        <menu-events-table
            class="wm-new-event"
            v-show="newEvent"
            :events="eventsWithNew"
            :all-events="events"
            ref="menuEventsTable"
        />
    </div>
</template>

<script>
import { uniqueKey } from '@/common/utils'
import MenuEventsTable from '@/components/MenuEventsTable'
import createEvents from '@/common/create-events'

export default {
    name: 'ContentEvent',
    components: {
        MenuEventsTable,
    },
    mixins: [
        createEvents,
    ],
    data() {
        return {
            newEvent: null,
            saving: false,
        }
    },
    props: {
        events: Array,
        hasError: Boolean,
        errorText: String,
    },
    computed: {
        eventsWithNew() {
            return this.newEvent
                ? [this.newEvent]
                : []
        },
    },
    methods: {
        onNewEvent() {
            this.newEvent = {
                key: uniqueKey(),
                type: 'callback',
                content: '',
            }

            this.$nextTick(() => {
                this.$refs.menuEventsTable.$refs.inputs[0].focus()
            })
        },
        async onSaveEvent() {
            const events = [
                ...this.events,
                {
                    ...this.newEvent,
                },
            ]

            const res = await this.onSave(this.$refs.menuEventsTable.$v, events, 'menu_events')

            if (res && res.data.status) {
                this.events.push(this.newEvent)
                this.$nextTick(() => {
                    this.$global.currentMenu.key = this.newEvent.key
                    this.newEvent = null
                })
            }
        },
    },
}
</script>

<style scoped lang="scss">
.wm-click-item {
    .wm-label {
        width: 80px;
    }
}

.wm-new-event {
    margin-top: 20px;
}
</style>
