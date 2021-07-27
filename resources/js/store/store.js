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
    current_inbox: 'INBOX',
    email_batch: null,
    current_page: 0,
    max_page: 0,
    inbox_items: 0,
    inbox_total: 0,
    username: null,
    user_email: null,
    user_labels: null,
    user_profile_photo: null,
    dropdown_btn_lbl: false,
    dropdown_btn_mv: false,
    dropdown_btn_user: false,
    dropdown_btn_email_data: false,
    selected_items_count: 0,
    selected_items_dataID: [],
    selected_item_unread: 0,
    selected_email_html_body: null,
    selected_email_data: null,
    selected_email_attachments: null,
    splitter_height: null,
    splitter_pane_0_height: null,
  },

  mutations:{
    set_routes(state, payload){
      state.routes = payload;
    },

    set_csrf_token(state, payload){
      state.csrf_token = payload;
    },

    set_current_inbox(state, payload){
      state.current_inbox = payload;
    },

    set_email_batch(state, payload){
      state.email_batch = payload;
    },

    modify_email_batch(state, payload){
      state.email_batch[payload.index][payload.property] = payload.value;
    },

    set_current_page(state, payload){
      state.current_page = payload;
    },

    set_max_page(state, payload){
      state.max_page = payload;
    },

    set_inbox_items(state, payload){
      state.inbox_items = payload;
    },

    set_inbox_total(state, payload){
      state.inbox_total = payload
    },

    set_user_email(state, payload){
      state.user_email = payload
    },

    set_user_labels(state, payload){
      payload.sort((a,b) => (a.text > b.text) ? 1 : ((b.text > a.text) ? -1 : 0));
      state.user_labels = payload;
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

    set_email_html_body(state, payload){
      state.selected_email_html_body = payload;
    },

    set_email_data(state, payload){
      state.selected_email_data = payload;
    },

    set_email_attachments(state, payload){
      state.selected_email_attachments = payload;
    },

    dropdown_btn_lbl_toggle(state, payload){
      state.dropdown_btn_lbl = payload;
    },

    dropdown_btn_mv_toggle(state, payload){
      state.dropdown_btn_mv = payload;
    },

    dropdown_btn_user_toggle(state, payload){
      state.dropdown_btn_user = payload;
    },

    dropdown_btn_email_data_toggle(state, payload){
      state.dropdown_btn_email_data = payload;
    },

    set_splitter_height(state, payload){
      state.splitter_height = payload;
    },

    set_splitter_pane_0_height(state, payload){
      state.splitter_pane_0_height = payload;
    }
  },

  actions:{
    set_routes(state, payload){
      state.commit("set_routes", payload);
    },

    set_csrf_token(state, payload){
      state.commit("set_csrf_token", payload);
    },

    set_current_inbox(state, payload){
      state.commit("set_current_inbox", payload);
    },

    set_email_batch(state, payload){
      state.commit("set_email_batch", payload);
    },

    modify_email_batch(state, payload){
      state.commit("modify_email_batch", payload);
    },

    set_current_page(state, payload){
      state.commit("set_current_page", payload);
    },

    set_max_page(state, payload){
      state.commit("set_max_page", payload)
    },

    set_inbox_items(state, payload){
      state.commit("set_inbox_items", payload);
    },

    set_inbox_total(state, payload){
      state.commit("set_inbox_total", payload);
    },

    set_user_email(state, payload){
      state.commit("set_user_email", payload);
    },

    set_user_labels(state, payload){
      state.commit("set_user_labels", payload);
    },

    set_user_profile_photo(state, payload){
      state.commit("set_user_profile_photo", payload);
    },

    set_selected_items_count(state, payload){
      state.commit("set_selected_items_count", payload);
    },

    set_selected_items_dataID(state, payload){
      state.commit("set_selected_items_dataID", payload);
    },

    set_email_html_body(state, payload){
      state.commit("set_email_html_body", payload);
    },

    set_email_data(state, payload){
      state.commit("set_email_data", payload);
    },

    set_email_attachments(state, payload){
      state.commit("set_email_attachments", payload);
    },

    dropdown_btn_lbl_toggle(state, payload){
      state.commit("dropdown_btn_lbl_toggle", payload);
    },

    dropdown_btn_mv_toggle(state, payload){
      state.commit("dropdown_btn_mv_toggle", payload);
    },

    dropdown_btn_user_toggle(state, payload){
      state.commit("dropdown_btn_user_toggle", payload);
    },

    dropdown_btn_email_data_toggle(state, payload){
      state.commit("dropdown_btn_email_data_toggle", payload);
    },

    set_splitter_height(state, payload){
      state.commit("set_splitter_height", payload);
    },

    set_splitter_pane_0_height(state, payload){
      state.commit("set_splitter_pane_0_height", payload);
    }
  },

  getters:{

  },


});