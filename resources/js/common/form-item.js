const props = {
    label: [String, Number],
    hasError: Boolean,
    errorText: String,
    inline: Boolean,
    errorInside: Boolean,
}

export default {
    props,
    computed: {
        _formItemProps() {
            const t = {}

            Object.keys(props).forEach((p) => {
                t[p] = this[p]
            })

            return t
        },
    },
}
