<template>
    <div class="media-list">
        <div
            v-show="loading"
            class="items-loading"
        >
            加载中
            <loading-dots
                :loading="loading"
                :num="6"
            />
        </div>
        <div
            v-show="!loading"
            class="items"
        >
            <media-item
                v-for="(item, index) of items"
                :key="index"
                :item="item"
                :type="type"
                @click.native="onPickerMedia(item)"
            />
        </div>
        <div class="paginator">
            <paginator
                :value="material.page"
                :per="$global.materialPerPage"
                :total="total"
                @input="onPageChange"
            />
        </div>
    </div>
</template>

<script>
import { getResources } from '@/api/wechat'
import Paginator from '@/components/Paginator'
import MediaItem from '@/components/media-picker/MediaItem'

export default {
    name: 'MediaList',
    components: {
        Paginator,
        MediaItem,
    },
    data() {
        return {
            loading: false,
        }
    },
    props: {
        type: String,
        value: Object,
    },
    computed: {
        offset() {
            return (this.material.page - 1) * this.$global.materialPerPage
        },
        items() {
            return this.material.items.slice(this.offset, this.offset + this.$global.materialPerPage)
        },
        totalPage() {
            return Math.ceil(this.total / this.$global.materialPerPage)
        },
        material() {
            return this.$global[this.type]
        },
        total() {
            if (this.type == 'image') {
                return this.material.items.length
            } else {
                return this.material.total
            }
        },
    },
    methods: {
        async getData(page) {
            if (this.loading) {
                return
            }

            // 如果是请求页大于当前最大页数，且被标记为没有数据了，则不请求
            if (this.material.bottom && page > this.totalPage) {
                return
            }

            // 如果该页数，曾请求过，则不用再请求，已经缓存了本页的数据
            if (this.material.pages.indexOf(page) !== -1) {
                this.material.page = page
                return
            }

            let data
            this.loading = true
            try {
                (
                    { data } = await getResources('materials', {
                        material_type: this.type,
                        page: page,
                    })
                )
            } finally {
                this.loading = false
            }


            if (data.status) {
                const d = data.data

                // 由于获取到数据之前，可能切换类型，
                // 所以获取到数据之后，要操作的素材类型，应该用返回的数据中的类型来获取
                const material = this.$global[d.type]

                if (this.type != 'image') {
                    material.total = d.total_count
                    material.bottom = true
                }

                if (d.item.length == 0) {
                    material.bottom = true
                    return
                }

                const offset = (page - 1) * this.$global.materialPerPage

                this.$global.materialPerPage = d.per_page
                material.pages.push(page)

                // 在全局数据中，缓存当前页数据
                // 假设曾请求过第 1、3 页的数据，则生成的数据结构如下:
                // [0, 1, 2, empty, empty, empty, 6, 7, 8]
                if (material.items.length < offset) {
                    material.items[offset] = null
                    material.items.splice(offset, 1, ...d.item)
                } else {
                    material.items.splice(offset, this.$global.materialPerPage, ...d.item)
                }

                material.page = page

                if (d.item.length < this.$global.materialPerPage) {
                    material.bottom = true
                }
            }
        },
        onPageChange(page) {
            this.clearSelect()
            this.getData(page)
        },
        onPickerMedia(item) {
            this.$bus.$emit('mediaSelected', item)
            this.$emit('input', this.convertData(item))
        },
        convertData(item) {
            if (this.type == 'news') {
                const items = item.content.news_item.map(i => {
                    const { title, digest, thumb_url, url } = i
                    return {
                        title,
                        digest,
                        thumb_url,
                        url,
                    }
                })
                return {
                    media_id: item.media_id,
                    content: {
                        news_item: items,
                    },
                }
            } else {
                return item
            }
        },
        clearSelect() {
            this.$bus.$emit('mediaSelected', null)
            this.$emit('input', null)
        },
    },
    watch: {
        type: {
            handler() {
                this.clearSelect()
                this.getData(this.material.page)
            },
            immediate: true,
        },
    },
}
</script>

<style scoped lang="scss">
@import "~@/../sass/vars";

.media-list {
    position: relative;
}

.items-loading,
.items {
    height: 285px;
}

.items-loading {
    text-align: center;
    padding-top: 100px;
    font-size: 20px !important;
    color: $main-color;
}

.items {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 18px;
    height: 285px;
    overflow-x: hidden;
    overflow-y: auto;
}
</style>
