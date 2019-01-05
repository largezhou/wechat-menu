import Vue from 'vue'

Vue.component('menus-editor', require('@/components/MenusEditor').default)
Vue.component('menu-events-setting', require('@/components/MenuEventsSetting').default)
Vue.component('other-events-setting', require('@/components/OtherEventsSetting').default)
Vue.component('w-input', require('@/components/form/WInput').default)
Vue.component('w-select', require('@/components/form/WSelect').default)
Vue.component('w-radio', require('@/components/form/WRadio').default)
Vue.component('w-radio-item', require('@/components/form/WRadioItem').default)
Vue.component('form-item', require('@/components/form/FormItem').default)
