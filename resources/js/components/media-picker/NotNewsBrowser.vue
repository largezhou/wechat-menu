<template>
    <div>
        <paginator
            :value="currentPage"
            :per="$global.materialPerPage"
            :total="$global.images.length"
            @input="onPageChange"
        />
        <ul>
            <li
                v-for="(item, index) of items"
                :key="index"
            >
                <img width="100" height="100" :src="item.url"/>
            </li>
        </ul>
    </div>
</template>

<script>
import { getResources } from '@/api/wechat'
import Paginator from '@/components/Paginator'

export default {
    name: 'NotNewsBrowser',
    components: {
        Paginator,
    },
    data() {
        return {
            currentPage: 1,
        }
    },
    computed: {
        offset() {
            return (this.currentPage - 1) * this.$global.materialPerPage
        },
        items() {
            return this.$global.images.slice(this.offset, this.offset + this.$global.materialPerPage)
        },
        totalPage() {
            return Math.ceil(this.$global.images.length / this.$global.materialPerPage)
        },
    },
    created() {
        this.getData(this.currentPage)
    },
    methods: {
        async getData(page) {
            if (this.$global.imagesBottom && page > this.totalPage) {
                return
            }

            if (this.$global.imagesPages.indexOf(page) !== -1) {
                this.currentPage = page
                return
            }

            // 如果该页数，曾请求过，则不用再请求，已经缓存了本页的数据
            const { data } = await getResources('materials', {
                material_type: 'image',
                page: page,
            })

            if (data.status) {
                const d = data.data

                if (d.item.length == 0) {
                    this.$global.imagesBottom = true
                    return
                }

                const offset =  (page - 1) * this.$global.materialPerPage

                this.$global.materialPerPage = d.per_page
                this.$global.imagesPages.push(page)

                // 在全局数据中，缓存当前页数据
                // 假设曾请求过第 1、3 页的数据，则生成的数据结构如下:
                // [0, 1, 2, empty, empty, empty, 6, 7, 8]
                if (this.$global.images.length < offset) {
                    this.$global.images[offset] = null
                    this.$global.images.splice(offset, 1, ...d.item)
                } else {
                    this.$global.images.splice(offset, this.$global.materialPerPage, ...d.item)
                }

                this.currentPage = page

                if (d.item.length < this.$global.materialPerPage) {
                    this.$global.imagesBottom = true
                }
            }
        },
        onPageChange(page) {
            this.getData(page)
        },
    },
}
</script>

<style scoped lang="scss">

</style>
