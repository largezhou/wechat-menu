export default {
    name: 'RenderContent',
    props: {
        h: {
            type: Function,
            required: true,
        },
    },
    render(h) {
        return this.h(h)
    },
}
