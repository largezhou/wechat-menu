<template>
    <form-item
        v-bind="_formItemProps"
    >
        <select
            :value="value"
            @input="$emit('input', $event.target.value)"
            class="input"
        >
            <slot/>
        </select>
    </form-item>
</template>

<script>
import FormItem from '@/components/form/FormItem'
import FormItemHelper from '@/common/form-item'

export default {
    name: 'WSelect',
    components: {
        FormItem,
    },
    mixins: [
        FormItemHelper,
    ],
    props: {
        value: [String, Number],
    },
}
</script>
