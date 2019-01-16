import { getResources } from '@/api/wechat'
import Vue from 'vue'
import axios from '@/api/wechat'

export default {
    data() {
        return {
            videoLinkLoading: false,
        }
    },
    methods: {
        // 视频和图文集的查看
        async onView(item, realType) {
            if (realType == 'video') {
                if (this.videoLinkLoading) {
                    return
                }

                // 视频的话，由于获取媒体详情后，返回的是一个链接，
                // 所以请求后，设置 url，如果有 url 就不用再请求了，
                // 点击可跳转到视频页
                if (item.url) {
                    return
                }

                this.videoLinkLoading = true
                let data
                try {
                    (
                        { data } = await getResources('media', {
                            media_id: item.media_id,
                        })
                    )
                } finally {
                    this.videoLinkLoading = false
                }

                if (data.status) {
                    Vue.set(item, 'url', data.data.down_url)
                    Vue.$notice({
                        msg: '视频链接获取成功，请重新点击打开视频链接',
                        type: 'success',
                    })
                }
            } else if (realType == 'news') {
                // 图文的话要打开一个弹窗，把里面所有图文列出来
                Vue.$dialog({
                    title: '图文集',
                    width: '560px',
                    height: '290px',
                    content: (h) => {
                        return h('news-view', {
                            props: {
                                news: item,
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

        viewLink(item, realType) {
            switch (realType) {
                case 'newsItem':
                case 'image':
                    return item.url
                case 'voice':
                    // 音频可直接下载
                    return `${axios.defaults.baseURL}/resources?type=media&media_id=${item.media_id}`
                case 'video':
                    // 视频如果有 url，说明已经获取过了，直接跳转链接
                    // 否则会执行 onView
                    if (item.url) {
                        return item.url
                    }
                default:
                    return 'javascript:void(0);'
            }
        },
    },
}
