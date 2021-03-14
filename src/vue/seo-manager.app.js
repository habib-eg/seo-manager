import Vue from 'vue'

import vuexI18n from 'vuex-i18n';
import Locales from './vue-i18n-locales.generated.js';


import VueResource from 'vue-resource'
import App from './App'
import MultiSelect from 'vue-multiselect'
import VueDraggable from 'vue-draggable'
import ClickOutside from 'vue-click-outside'
import VueSweetalert2 from 'vue-sweetalert2'
import {store} from './store/store'


Vue.use(vuexI18n.plugin, store);

Vue.i18n.add('en', Locales.en);
Vue.i18n.add('ar', Locales.ar);

// set the start locale to use
Vue.i18n.set(localStorage.getItem('locale'));

Vue.use(VueDraggable);
Vue.use(VueResource);
Vue.use(VueSweetalert2);
require('@fortawesome/fontawesome-free/js/all.min');
require('@fortawesome/fontawesome-free/css/all.min.css');

const API_URL = window.API_URL;
const CSRF_TOKEN = window.CSRF_TOKEN || $('meta[name="csrf-token"]').attr('content');
console.log(API_ASSETS)
Vue.filter('uppercase', function (value) {
    return value.toUpperCase()
});
Vue.directive('click-outside', ClickOutside);
Vue.component('multi-select', MultiSelect);

new Vue({
    el: '#lionix-seo-manager-app',
    store,
    components: {
        App
    },
});
