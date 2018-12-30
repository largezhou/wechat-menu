<template>
    <div>
        <div class="form-item click-item">
            <span class="label">选择事件</span>
            <select
                v-model="$global.currentMenu.key"
                class="input"
            >
                <option
                    v-for="e of events"
                    :key="e.key"
                    :value="e.key"
                    v-text="e.remark"
                />
            </select>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ContentEvent',
    props: {
        events: Object,
    },
    computed: {
        currentEvent() {
            const key = this.$global.currentMenu.key
            return this.events[key]
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
</style>
