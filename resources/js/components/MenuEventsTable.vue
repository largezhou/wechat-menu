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
                <w-input
                    v-model="e.remark"
                    :has-error="hasError('remark', index)"
                    :error-text="getError('remark', index)"
                    error-inside
                />
            </td>
            <td>
                <w-input
                    v-model="e.key"
                    :has-error="hasError('key', index)"
                    :error-text="getError('key', index)"
                    error-inside
                />
            </td>
            <td>
                <change-handle-type :event="e"/>
            </td>
            <td>
                <w-textarea
                    v-if="e.type == 'msg'"
                    v-model="e.content"
                    ref="inputs"
                    rows="2"
                    :has-error="hasError('content', index)"
                    :error-text="getError('content', index)"
                    error-inside
                />
                <w-callback-input
                    v-else
                    v-model="e.content"
                    :events="eventsForCallbacks"
                    ref="inputs"
                    group="menuEvents"
                    :has-error="hasError('content', index)"
                    :error-text="getError('content', index)"
                    error-inside
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
import { required } from 'vuelidate/lib/validators'
import { callback, unique } from '@/common/validators'
import EventErrorHelper from '@/common/event-error-helper'

export default {
    name: 'MenuEventsTable',
    components: {
        CallbackInput,
        ChangeHandleType,
    },
    mixins: [
        EventErrorHelper,
    ],
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
            fieldErrors: {
                remark: {
                    required: '必须填写',
                    unique: '重复',
                },
                key: {
                    required: '必须填写',
                    unique: '重复',
                },
                content: {
                    required: '必须填写',
                    callback: '不是有效的回调',
                },
            },
        }
    },
    validations() {
        return {
            events: {
                $each: {
                    remark: {
                        required,
                        // 如果有 allEvents ，则表示该组件是用来快速添加的，
                        // 此时，新添加的那条记录，暂时没有 push 到 events 数组中
                        // 所以，为了判断是否重复，这里的 excludeSelf 设置为 false
                        unique: unique(this.remarks, !this.allEvents),
                    },
                    key: {
                        required,
                        unique: unique(this.keys, !this.allEvents),
                    },
                    content: {
                        required,
                        callback,
                    },
                },
            },
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
        remarks() {
            const events = this.allEvents || this.events

            return events.map(e => e.remark)
        },
        keys() {
            const events = this.allEvents || this.events

            return events.map(e => e.key)
        },
    },
}
</script>
