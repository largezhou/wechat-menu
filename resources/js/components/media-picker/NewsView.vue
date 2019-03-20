<template>
    <div class="wm-news-view">
        <media-item
            v-for="(item, index) of newsItems"
            :key="index"
            :item="item"
            type="newsItem"
        />
    </div>
</template>

<script>
import MediaItem from '@/components/media-picker/MediaItem'

export default {
    name: 'NewsView',
    components: {
        MediaItem,
    },
    props: {
        news: Object,
    },
    computed: {
        newsItems() {
            return this.news.content.news_item
        },
    },
}
</script>

<style scoped lang="scss">
.wm-news-view {
    display: flex;
    flex-wrap: wrap;
}
</style>
