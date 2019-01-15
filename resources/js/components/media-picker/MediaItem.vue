<template>
    <div
        class="item"
        :class="{ active }"
    >
        <img
            v-if="imgUrl"
            :src="imgUrl"
            :class="{ logo: realType == 'voice' || realType == 'video' }"
        />
        <span
            class="item-title"
            :title="title"
        >{{ title }}</span>
        <a
            target="_blank"
            :href="viewLink"
            class="view"
            @click.stop="onView"
        >查看</a>
    </div>
</template>

<script>
import axios from '@/api/wechat'
import { getResources } from '@/api/wechat'

export default {
    name: 'MediaItem',
    props: {
        item: {
            type: Object,
            required: true,
        },
        type: {
            type: String,
            required: true,
        },
        active: Boolean,
    },
    computed: {
        realType() {
            if (this.type != 'news') {
                return this.type
            }

            // news 类型再细分为 news（图文集）和 newsItem （单个图文）类型
            // 通过 content 判断
            if (typeof this.item.content == 'string') {
                return 'newsItem'
            } else {
                return 'news'
            }
        },
        imgUrl() {
            switch (this.realType) {
                // 图文集，则返回图文集中的第一篇文章的封面
                case 'news':
                    return this.item.content.news_item[0].thumb_url
                // 单个图文，直接获取封面
                case 'newsItem':
                    return this.item.thumb_url
                case 'image':
                    return this.item.url
                // 视频和音频，则用图标
                case 'voice':
                case 'video':
                    return require(`@/../img/${this.realType}.png`)
                default:
                    return ''
            }
        },
        title() {
            switch (this.realType) {
                case 'news':
                    return this.item.content.news_item[0].title
                case 'newsItem':
                    return this.item.title
                default:
                    return this.item.name
            }
        },
        viewLink() {
            switch (this.realType) {
                case 'newsItem':
                case 'image':
                    return this.item.url
                case 'voice':
                    // 音频可直接下载
                    return `${axios.defaults.baseURL}/resources?type=media&media_id=${this.item.media_id}`
                case 'video':
                    // 视频如果有 url，说明已经获取过了，直接跳转链接
                    // 否则会执行 onView
                    if (this.item.url) {
                        return this.item.url
                    }
                default:
                    return 'javascript:void(0);'
            }
        },
    },
    methods: {
        async onView() {
            if (this.realType == 'video') {
                // 视频的话，由于获取媒体详情后，返回的是一个链接，
                // 所以请求后，设置 url，如果有 url 就不用再请求了，
                // 点击可跳转到视频页
                if (this.item.url) {
                    return
                }

                const { data } = await getResources('media', {
                    media_id: this.item.media_id,
                })

                if (data.status) {
                    this.$set(this.item, 'url', data.data.down_url)
                    this.$notice({
                        msg: '视频链接获取成功，请重新点击【查看】打开视频',
                        type: 'success',
                    })
                }
            } else if (this.realType == 'news') {
                // 图文的话要打开一个弹窗，把里面所有图文列出来
                this.$dialog({
                    title: '图文集',
                    width: '560px',
                    height: '290px',
                    content: (h) => {
                        return h('news-view', {
                            props: {
                                news: this.item,
                            },
                        })
                    },
                    buttons: [
                        {
                            class: 'btn',
                            text: '关闭',
                            callback(dialog) {
                                dialog.close()
                            },
                        },
                    ],
                })
            }
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars";

.item {
    border: 5px solid $grey;
    margin-left: 15px;
    margin-bottom: 15px;
    font-size: 0px !important;
    cursor: pointer;
    height: 110px;
    width: 110px;
    position: relative;

    &.active {
        border-color: $main-color;
    }

    img {
        height: 100%;
        width: 100%;

        &.logo {
            position: absolute;
            height: 50%;
            width: 50%;
            left: 25px;
            top: 10px;
        }
    }

    .item-title,
    .view {
        position: absolute;
        overflow: hidden;
        display: inline-block;
        background: rgba(231, 231, 235, 0.66);
    }

    .item-title {
        height: 20px;
        left: 0px;
        bottom: 0px;
        right: 0px;
    }

    .view {
        right: 0px;
        top: 0px;
        padding: 0 3px;
        display: none;
        color: #000 !important;
    }

    &:hover {
        .view {
            display: initial;
        }
    }
}
</style>
