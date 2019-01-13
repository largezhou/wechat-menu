<template>
    <div class="media-browser">
        <div class="tabs">
            <span
                class="tab"
                v-for="k of Object.keys(types)"
                :key="k"
                href="javascript:void(0)"
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
import { AUTO_REPLY_TYPES } from '../common/constants'

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
    },
    computed: {
        types() {
            return AUTO_REPLY_TYPES
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
