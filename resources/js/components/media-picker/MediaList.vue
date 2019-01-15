<template>
    <div class="media-list">
        <div class="items">
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
            // 如果是请求页大于当前最大页数，且被标记为没有数据了，则不请求
            if (this.material.bottom && page > this.totalPage) {
                return
            }

            // 如果该页数，曾请求过，则不用再请求，已经缓存了本页的数据
            if (this.material.pages.indexOf(page) !== -1) {
                this.material.page = page
                return
            }

            const { data } = await getResources('materials', {
                material_type: this.type,
                page: page,
            })

            if (data.status) {
                const d = data.data

                if (this.type != 'image') {
                    this.material.total = d.total_count
                    this.material.bottom = true
                }

                if (d.item.length == 0) {
                    this.material.bottom = true
                    return
                }

                const offset = (page - 1) * this.$global.materialPerPage

                this.$global.materialPerPage = d.per_page
                this.material.pages.push(page)

                // 在全局数据中，缓存当前页数据
                // 假设曾请求过第 1、3 页的数据，则生成的数据结构如下:
                // [0, 1, 2, empty, empty, empty, 6, 7, 8]
                if (this.material.items.length < offset) {
                    this.material.items[offset] = null
                    this.material.items.splice(offset, 1, ...d.item)
                } else {
                    this.material.items.splice(offset, this.$global.materialPerPage, ...d.item)
                }

                this.material.page = page

                if (d.item.length < this.$global.materialPerPage) {
                    this.material.bottom = true
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
                    return {
                        title: i.title,
                        description: i.digest,
                        image: i.thumb_url,
                        url: i.url,
                    }
                })
                return {
                    media_id: item.media_id,
                    items,
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

.paginator {
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