import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
Vue.config.devtools = true

export const store = new Vuex.Store({
  state:{
    message: "Vuex Active",
    counter: 0,
    routes: {},
    csrf_token: null,
    user_profile_photo: null,
    dropdown_btn_lbl: false,
    dropdown_btn_mv: false,
    dropdown_btn_user: false,
    selected_items_count: 0,
    selected_items_dataID: [],
    selected_item_unread: 0,
  },

  mutations:{
    set_routes(state, payload){
      state.routes = payload;
    },

    set_csrf_token(state, payload){
      state.csrf_token = payload;
    },

    set_user_profile_photo(state, payload){
      state.user_profile_photo = payload;
    },

    set_selected_items_count(state, payload){
      state.selected_items_count = payload;
    },

    set_selected_items_dataID(state, payload){
      state.selected_items_dataID = payload;
    },

    dropdown_btn_lbl_toggle(state, payload){
      state.dropdown_btn_lbl = payload;
    },

    dropdown_btn_mv_toggle(state, payload){
      state.dropdown_btn_mv = payload;
    },

    dropdown_btn_user_toggle(state, payload){
      state.dropdown_btn_user = payload;
    }
  },

  actions:{
    set_routes(state, payload){
      state.commit('set_routes', payload);
    },

    set_csrf_token(state, payload){
      state.commit('set_csrf_token', payload);
    },

    set_user_profile_photo(state, payload){
      state.commit('set_user_profile_photo', payload);
    },

    set_selected_items_count(state, payload){
      state.commit('set_selected_items_count', payload);
    },

    set_selected_items_dataID(state, payload){
      state.commit("set_selected_items_dataID", payload);
    },

    dropdown_btn_lbl_toggle(state, payload){
      state.commit('dropdown_btn_lbl_toggle', payload);
    },

    dropdown_btn_mv_toggle(state, payload){
      state.commit('dropdown_btn_mv_toggle', payload);
    },

    dropdown_btn_user_toggle(state, payload){
      state.commit('dropdown_btn_user_toggle', payload);
    }
  },

  getters:{

  },


});