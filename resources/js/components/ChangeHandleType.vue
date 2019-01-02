<template>
    <a
        href="javascript:void(0);"
        title="点击切换类型"
        @click="onChangeType()"
    >{{ typeText }}</a>
</template>

<script>
import { TYPES_TEXT } from '@/common/constants'

export default {
    name: 'ChangeHandleType',
    props: {
        event: Object,
    },
    computed: {
        typeText() {
            return TYPES_TEXT[this.event.type]
        },
    },
    methods: {
        onChangeType() {
            this.event.type = this.event.type == 'msg' ? 'callback' : 'msg'
        },
    },
}
</script>

<style scoped lang="scss">

</style>
