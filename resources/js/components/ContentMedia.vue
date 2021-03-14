<template>
    <div>
        <media-picker
            :value="{
                type: '',
                value: {
                    media_id: $global.currentMenu.media_id,
                },
            }"
            @input="handlePicked"
        />
        <w-input
            label="媒体ID"
            v-bind="$props"
            v-model="$global.currentMenu.media_id"
            inline
            error-inside
        />
    </div>
</template>

<script>
import MediaPicker from '@/components/media-picker/MediaPicker'
import { objGet } from '@/common/utils'

export default {
    name: 'ContentMedia',
    data: () => ({
        media: null,
    }),
    components: {
        MediaPicker,
    },
    props: {
        hasError: Boolean,
        errorText: String,
    },
    methods: {
        handlePicked(item) {
            log(item)
            this.$global.currentMenu.media_id = objGet(item, 'value.media_id')
        },
    },
}
</script>

<style lang="scss" scoped>
.wm-hint {
    display: block !important;
    margin-bottom: 15px;
}
</style>
