export default {
    methods: {
        hasError(field, index) {
            const v = this.$v.events.$each[index]

            return v[field].$invalid
        },
        getError(field, index) {
            const v = this.$v.events.$each[index][field]
            for (let i of Object.keys(v.$params)) {
                if (!v[i]) {
                    return this.fieldErrors[field][i]
                }
            }
        },
    },
}
