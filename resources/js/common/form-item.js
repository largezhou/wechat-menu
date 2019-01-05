export default {
    props: {
        label: [String, Number],
        hasError: Boolean,
        errorText: String,
        inline: Boolean,
    },
    computed: {
        _formItemProps() {
            const formItemProps = [
                'label',
                'hasError',
                'errorText',
                'inline',
            ]

            const props = {}

            formItemProps.forEach((p) => {
                props[p] = this[p]
            })

            return props
        },
    },
}
