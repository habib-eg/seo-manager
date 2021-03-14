import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        locale: null
    },
    mutations:{
        setLocale(state, locale){
            state.locale = locale
        }
    },
    getters: {
        locale: state => {
            return state.locale
        },
        path:state => {
            return  window.API_ASSETS = $('meta[name="path"]').attr('content');
        }
    }
});

