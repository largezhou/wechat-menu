export default {
    methods: {
        hasError(field) {
            const curV = this.currentV[field]
            return curV && curV.$invalid
        },
        getError(field) {
            const curV = this.currentV[field]
            if (!curV) {
                return
            }
            for (let i of Object.keys(curV.$params)) {
                if (!curV[i]) {
                    return this.fieldErrors[field][i]
                }
            }
        },
    },
}
