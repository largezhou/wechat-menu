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
        </div>
    </div>
</template>

<script>
import { AUTO_REPLY_TYPES } from '@/common/constants'

export default {
    name: 'MediaBrowser',
    data() {
        return {
            curType: this.initType || 'text',
            text: this.initText,
        }
    },
    props: {
        initText: String,
        initType: String,
        value: Object,
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
            }

            switch (this.curType) {
                case 'text':
                    val.text = this.text
                    break;
                case 'news':
                    val.items = []
                    break;
                case 'image':
                case 'voice':
                case 'video':
                    val.items = []
                    break;
            }

            this.$emit('input', val)
        },
    },
    watch: {
        curType() {
            this.onChange()
        },
        text() {
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
}
</style>
