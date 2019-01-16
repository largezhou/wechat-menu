<template>
    <div class="media-browser">
        <div class="tabs">
            <span
                class="tab"
                v-for="k of Object.keys(types)"
                :key="k"
                :class="{ active: curType == k }"
                @click="curType = k"
            >{{ types[k] }}</span>
        </div>
        <div class="tab-content">
            <w-textarea
                v-if="curType == 'text'"
                rows="5"
                v-model="text"
                ref="textarea"
            />
            <media-list
                v-else
                :type="curType"
                v-model="mediaDetail"
            />
        </div>
    </div>
</template>

<script>
import { AUTO_REPLY_TYPES } from '@/common/constants'
import MediaList from '@/components/media-picker/MediaList'

export default {
    name: 'MediaBrowser',
    components: {
        MediaList,
    },
    data() {
        return {
            curType: this.initType || 'text',
            text: typeof this.value == 'string' ? this.value : '',
            mediaDetail: null,
        }
    },
    props: {
        initType: String,
        value: [Object, String],
    },
    computed: {
        types() {
            return AUTO_REPLY_TYPES
        },
    },
    methods: {
        onChange() {
            const val = {
                type: this.curType,
                value: this.curType == 'text'
                    ? this.text
                    : this.mediaDetail
            }

            this.$emit('input', val)
        },
    },
    watch: {
        curType: {
            handler() {
                this.onChange()
            },
            immediate: true,
        },
        text: {
            handler() {
                this.onChange()
            },
            immediate: true,
        },
        mediaDetail() {
            this.onChange()
        },
    },
}
</script>

<style scoped lang="scss">
.media-browser {
    .tab {
        text-align: center;
        width: 120px;
    }

    height: 100%;
}
</style>
