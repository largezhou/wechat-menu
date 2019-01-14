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
            <div v-else-if="curType == 'news'">图文</div>
            <not-news-browser
                v-else
            />
        </div>
    </div>
</template>

<script>
import { AUTO_REPLY_TYPES } from '@/common/constants'
import NotNewsBrowser from '@/components/media-picker/NotNewsBrowser'

export default {
    name: 'MediaBrowser',
    components: {
        NotNewsBrowser,
    },
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
    created() {
        this.setHeadNoReferrer()
    },
    methods: {
        onChange() {
            const val = {
                type: this.curType,
            }

            switch (this.curType) {
                case 'text':
                    val.text = this.text
                    break
                case 'news':
                    val.items = []
                    break
                case 'image':
                case 'voice':
                case 'video':
                    val.items = []
                    break
            }

            this.$emit('input', val)
        },
        /**
         * 设置标签后，才能打开微信的图片，不然提示防盗链
         */
        setHeadNoReferrer() {
            let referrerEl = document.querySelector('meta[name=referrer]')
            if (referrerEl) {
                referrerEl.content = 'never'
            } else {
                referrerEl = document.createElement('meta')
                referrerEl.name = 'referrer'
                referrerEl.content = 'never'
                document.head.prepend(referrerEl)
            }
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
