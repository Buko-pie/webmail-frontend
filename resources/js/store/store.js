import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
Vue.config.devtools = true

export const store = new Vuex.Store({
  state:{
    message: "Vuex Active",
    counter: 0,
    dropdown_btn_lbl: false,
    dropdown_btn_mv: false
  },

  mutations:{
    dropdown_btn_lbl_toggle(state, payload){
      state.dropdown_btn_lbl = payload;
    },

    dropdown_btn_mv_toggle(state, payload){
      state.dropdown_btn_lbl = payload;
    }
  },

  actions:{
    dropdown_btn_lbl_toggle(state, payload){
      state.commit('dropdown_btn_lbl_toggle', payload);
    },

    dropdown_btn_mv_toggle(state, payload){
      state.commit('dropdown_btn_mv_toggle', payload);
    }
  },

  getters:{

  },


});