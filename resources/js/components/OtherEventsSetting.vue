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

                <td
                    v-if="isDefaultEvent(e)"
                >
                    {{ typeText(e.key) }}({{ e.key }})
                </td>
                <td
                    v-else
                    class="form-item"
                >
                    <div class="content table-content">
                        <input
                            type="text"
                            class="input table-input"
                            :class="{ 'has-error': hasError('key', index) }"
                            v-model="e.key"
                        />
                        <span class="error-text">{{ getError('key', index) }}</span>
                    </div>
                </td>

                <td>
                    <change-handle-type :event="e"/>
                </td>
                <td class="table-content">
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
                        :events="events"
                        group="otherEvents"
                        ref="inputs"
                        :has-error="hasError('content', index)"
                        :error-text="getError('content', index)"
                    />
                </td>
                <td>
                    <button
                        v-if="!isDefaultEvent(e)"
                        class="btn btn-danger btn-sm"
                        @click="onRemove(index)"
                    >删除</button>
                </td>
            </tr>
        </table>
        <div class="table-footer">
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
import ChangeHandleType from '@/components/ChangeHandleType'
import CallbackInput from '@/components/CallbackInput'
import { OTHER_EVENT_TYPES } from '@/common/constants'
import { getSettings, saveSettings } from '@/api/wechat'
import { required } from 'vuelidate/lib/validators'
import { callback } from '@/common/validators'

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
                    width: '200',
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
                {
                    field: 'actions',
                    name: '操作',
                    width: '80',
                },
            ],
            events: [],
            saving: false,

            fieldErrors: {
                key: {
                    required: '必须填写',
                },
                content: {
                    required: '必须填写',
                    callback: '不是有效的回调',
                },
            },
        }
    },
    validations: {
        events: {
            $each: {
                key: {
                    required,
                },
                content: {
                    callback,
                },
            },
        },
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
            this.events = data.data
            this.initDefaultEvents()
        },
        /**
         * 初始化事件设定，比如首次使用时，一些默认的事件用户是没有配置的。或者之后用户删除了某些默认事件
         */
        initDefaultEvents() {
            // 预设事件，就是 OTHER_EVENT_TYPES 常量中的
            const defaultEvents = []
            // 用户添加的其他事件，可能是我漏掉没有放到预设中的，或者公众号后期新增的
            const userEvents = []
            // key 与 事件配置 的键值对
            const mappedEvents = {}

            this.events.forEach(e => {
                if (OTHER_EVENT_TYPES[e.key]) {
                    mappedEvents[e.key] = e
                } else {
                    userEvents.push(e)
                }
            })

            // 如果请求获取的事件配置数据中，没有某些预设事件的配置，则给个默认的
            for (let key of Object.keys(OTHER_EVENT_TYPES)) {
                if (!mappedEvents[key]) {
                    defaultEvents.push({
                        key,
                        type: 'callback',
                        content: '',
                    })
                } else {
                    defaultEvents.push(mappedEvents[key])
                }
            }

            // 把预设的放前面，用户自行添加的全部放后面
            this.events = [...defaultEvents, ...userEvents]
            this.eventsBak = JSON.stringify(this.events)
        },
        onNewEvent() {
            this.events.push({
                key: '',
                type: 'callback',
                content: '',
            })

            this.$nextTick(() => {
                this.$refs.inputs[this.events.length - 1].focus()
            })
        },
        onReset() {
            this.events = JSON.parse(this.eventsBak)
        },
        async onSave() {
            if (this.saving) {
                return
            }

            this.$v.$touch()

            if (this.$v.$invalid) {
                this.$notice({
                    msg: '请填写完正确的配置',
                    type: 'error',
                })
                return
            }

            try {
                this.saving = true
                return await saveSettings('other_events', this.events)
            } finally {
                this.saving = false
            }
        },
        isDefaultEvent(event) {
            return !!OTHER_EVENT_TYPES[event.key]
        },
        onRemove(index) {
            const e = this.events[index]
            if (this.isDefaultEvent(e)) {
                this.$notice({
                    msg: '不能删除预设的事件',
                    type: 'error',
                })
                return
            }

            confirm('确定删除？') && this.events.splice(index, 1)
        },
        hasError(field, index) {
            const v = this.$v.events.$each[index]

            return v[field].$invalid
        },
        getError(field, index) {
            const v = this.$v.events.$each[index][field]
            for (let i of Object.keys(v.$params)) {
                if (!v[i]) {
                    return this.fieldErrors[field][i]
                }
            }
        },
    },
}
</script>

<style scoped lang="scss">
.other-events-setting {
    width: 1000px;
}
</style>
