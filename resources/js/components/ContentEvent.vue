<template>
    <div>
        <w-select
            class="vertical-middle"
            label="选择事件"
            :has-error="hasError"
            :error-text="errorText"
            v-model="$global.currentMenu.key"
            inline
        >
            <option
                v-for="e of events"
                :key="e.key"
                :value="e.key"
                v-text="e.remark"
            />
        </w-select>
        <div class="vertical-middle">
            <button
                v-show="!newEvent"
                type="button"
                class="btn btn-primary btn-sm"
                @click="onNewEvent"
            >添加</button>
            <div v-show="newEvent" style="display: inline-block">
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="onSaveEvent"
                    :disabled="saving"
                >确定</button>
                <button
                    type="button"
                    class="btn btn-sm"
                    @click="newEvent = null"
                >取消</button>
            </div>
        </div>

        <menu-events-table
            class="new-event"
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
import CreateEvents from '@/common/create-events'

export default {
    name: 'ContentEvent',
    components: {
        MenuEventsTable,
    },
    mixins: [
        CreateEvents,
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

            const { data } = await this.onSave(events)

            if (data.status) {
                this.events.push(this.newEvent)
                this.$global.currentMenu.key = this.newEvent.key
                this.newEvent = null
            }
        },
    },
}
</script>

<style scoped lang="scss">
.click-item {
    .label {
        width: 80px;
    }
}

.new-event {
    margin-top: 20px;
}
</style>
