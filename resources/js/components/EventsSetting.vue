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
                            :data="callbacks"
                            ref="inputs"
                            @blur="onCallbackChange"
                        />
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
                :disabled="saving"
            >保存</button>
            <button
                class="btn"
                @click="onNewEvent"
            >添加一个</button>
        </div>
    </div>
</template>

<script>
import { createEvents, getEvents } from '@/api/wechat'
import CallbackInput from '@/components/CallbackInput'

const TYPES_TEXT = {
    msg: '自动回复',
    callback: '事件处理',
}

export default {
    name: 'EventsSetting',
    components: {
        CallbackInput,
    },
    data() {
        return {
            events: [],
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

            this.events.forEach(e => {
                e.type == 'callback' && this.onCallbackChange(e.content)
            })
        },
        onRemove(index) {
            this.events.splice(index, 1)
        },
        onNewEvent() {
            this.events.push({
                key: Math.random().toString(32).substr(2),
                type: 'msg',
                content: '',
            })

            this.$nextTick(() => {
                this.$refs.inputs[this.events.length - 1].focus()
            })
        },
        async onSave() {
            const valid = this.valid()
            if (valid !== true) {
                alert(valid)
                return
            }

            try {
                this.saving = true
                const { data } = await createEvents(this.events)
                alert(data.errmsg)
            } finally {
                this.saving = false
            }
        },
        typeText(type) {
            return TYPES_TEXT[type]
        },
        onChangeType(index) {
            const event = this.events[index]
            event.type = event.type == 'msg' ? 'callback' : 'msg'
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
        onCallbackChange(val) {
            const t = val.split('@')
            if (t.length == 2 && this.callbacks.indexOf(t[0]) === -1) {
                this.callbacks.push(t[0])
            }
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars.scss";

.events-setting {
    width: 1000px;
}

.events-table {
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

.empty-table {
    text-align: center;
    color: $grey-1;
    padding: 20px 0;
    border: $grey-border;
    border-top: none;
}

.event-type {
    cursor: pointer;
}

.table-input {
    width: 100% !important;
}
</style>
